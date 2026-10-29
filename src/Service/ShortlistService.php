<?php

declare(strict_types=1);

namespace Shortlist\Service;

use Shortlist\Contract\HasHooks;
use Shortlist\Repository\WishlistTableRepository;
use WPPoland\StorefrontKit\Wishlist\WishlistEngine;

defined('ABSPATH') || exit;

/**
 * Thin adapter over the storefront-kit {@see WishlistEngine}.
 *
 * Injects this plugin's text-domain ('shortlist'), option prefix ('shortlist_'),
 * asset URLs and labels into the namespace-neutral engine, and supplies the two
 * closures the engine needs: one to render the packaged loop/single buttons and
 * one to build the My Account / shortcode list HTML. All wishlist orchestration
 * (cookie, ownership, AJAX toggle, account endpoint, guest->user transfer) lives
 * in the kit; this class wires localisation, option storage, asset paths and the
 * `[shortlist]` shortcode. Storage is delegated to {@see WishlistTableRepository}.
 */
final class ShortlistService implements HasHooks
{
    private const OPTION = 'shortlist_settings';

    private const COUNT_ACTION = 'shortlist_wishlist_count';

    private ?WishlistEngine $engine = null;

    private bool $stringsAdded = false;

    public function __construct()
    {
        // The engine ships with storefront-kit >= 1.4.0. When present, wire it
        // with this plugin's text-domain / option prefix / asset URLs. Otherwise
        // leave the service inert (see registerHooks()).
        if (! class_exists(WishlistEngine::class)) {
            return;
        }

        $this->engine = new WishlistEngine(
            repository: new WishlistTableRepository(),
            ajaxAction: 'shortlist_wishlist_toggle',
            nonceAction: 'shortlist_wishlist',
            scriptObjectName: 'shortlistWishlist',
            assetHandle: 'shortlist',
            styleUrl: \Shortlist\Plugin::instance()->url('assets/css/wishlist.css'),
            scriptUrl: \Shortlist\Plugin::instance()->url('assets/js/wishlist.js'),
            version: \Shortlist\VERSION,
            endpoint: 'shortlist',
            guestCookie: 'shortlist_session',
            loopButtonTemplate: 'loop-wishlist-button',
            singleButtonTemplate: 'single-wishlist-button',
            accountTemplate: 'account-wishlist',
            labels: [
                'add'            => __('Add to wishlist', 'plogins-shortlist'),
                'remove'         => __('Remove from wishlist', 'plogins-shortlist'),
                'account'        => __('Wishlist', 'plogins-shortlist'),
                'login_required' => __('Please log in to use your wishlist.', 'plogins-shortlist'),
                'not_found'      => __('Product not found.', 'plogins-shortlist'),
                'variation_required' => __('Choose product options before adding to your wishlist.', 'plogins-shortlist'),
            ],
            isEnabled: fn (): bool => $this->isFeatureEnabled(),
            settings: fn (): array => $this->settings(),
            renderTemplate: function (string $template, array $context): void {
                $this->renderTemplate($template, $context);
            },
            renderAccount: fn (string $template, array $context): string => $this->renderAccount($template, $context),
            shortcodeTag: 'shortlist',
            blockName: 'shortlist/wishlist',
            elementorWidget: 'shortlist',
        );
    }

    public function registerHooks(): void
    {
        if (! $this->engine instanceof WishlistEngine) {
            // TODO: storefront-kit < 1.4.0 has no WishlistEngine. Bump the
            // `wppoland/storefront-kit` constraint (composer update) to enable
            // the wishlist. No hooks are registered until the engine is present.
            return;
        }

        $this->engine->registerHooks();
        add_shortcode('shortlist', [$this, 'renderShortcode']);
        add_shortcode('shortlist_count', [$this, 'renderCountShortcode']);
        add_action('wp_ajax_' . self::COUNT_ACTION, [$this, 'handleCount']);
        add_action('wp_ajax_nopriv_' . self::COUNT_ACTION, [$this, 'handleCount']);

        // Block themes: Product Collection cards carry no classic loop hook, so
        // the button goes next to the card's add-to-cart button block.
        add_filter('render_block_woocommerce/product-button', [$this, 'appendBlockButton'], 10, 3);
        add_action('woocommerce_add_to_cart', [$this, 'removeOnAddToCart'], 10, 4);
        // A product already in the cart is not added again: the block button
        // raises its quantity instead, which never fires woocommerce_add_to_cart.
        add_action('woocommerce_after_cart_item_quantity_update', [$this, 'removeOnQuantityIncrease'], 10, 4);

        // Extend the kit's localized config object with translatable status
        // strings used by our script's aria-live announcements. Runs after the
        // engine's own enqueue (priority 10) so the handle exists.
        add_action('wp_enqueue_scripts', [$this, 'localizeStrings'], 20);
        add_action('wp_enqueue_scripts', [$this, 'enqueueVariationDependency'], 20);

        // Append the saved-item count to the My Account "Wishlist" menu label.
        // The kit engine adds the menu item on the same filter at the default
        // priority (10); run later (20) so the count reflects the final label.
        add_filter('woocommerce_account_menu_items', [$this, 'appendAccountCount'], 20);

        // Cross-plugin ecosystem integrations.
        add_action('shortlist_after_wishlist_items', [$this, 'renderQuoteIntegration'], 10, 2);
        add_action('shortlist_wishlist_item_actions', [$this, 'renderWaitlistIntegration'], 10, 2);
        add_action('template_redirect', [$this, 'maybeHandleQuoteRequest']);
    }

    /**
     * Append the saved-item count to the My Account wishlist menu label, e.g.
     * "Wishlist (3)". No-op when the count toggle is off or the item is absent.
     *
     * @param array<string, string> $items
     * @return array<string, string>
     */
    public function appendAccountCount(array $items): array
    {
        if (! $this->engine instanceof WishlistEngine) {
            return $items;
        }

        $settings = $this->settings();

        if (empty($settings['show_account_count']) || empty($settings['show_in_account'])) {
            return $items;
        }

        if (! isset($items['shortlist'])) {
            return $items;
        }

        $count = $this->engine->getCount();

        if ($count < 1) {
            return $items;
        }

        $items['shortlist'] = sprintf(
            /* translators: 1: wishlist menu label, 2: saved item count */
            _x('%1$s (%2$d)', 'My Account menu label with item count', 'plogins-shortlist'),
            $items['shortlist'],
            $count,
        );

        return $items;
    }

    /**
     * Merge translatable status strings into the kit's localized config object
     * (`shortlistWishlist`) so our front-end script can announce add/remove and
     * failure outcomes to assistive tech. Uses wp_add_inline_script so it merges
     * onto the existing object rather than replacing the kit's payload.
     */
    public function localizeStrings(): void
    {
        if ($this->stringsAdded || ! wp_script_is('shortlist', 'enqueued')) {
            return;
        }

        $this->stringsAdded = true;
        $settings           = $this->settings();

        $strings = [
            'countAction' => self::COUNT_ACTION,
            'removeOnCart' => ! empty($settings['remove_on_add_to_cart']),
            'addLabel'    => (string) ($settings['button_add_text'] ?? ''),
            'removeLabel' => (string) ($settings['button_remove_text'] ?? ''),
            'addedText'   => __('Added to your wishlist.', 'plogins-shortlist'),
            'removedText' => __('Removed from your wishlist.', 'plogins-shortlist'),
            'errorText'          => __('Sorry, something went wrong. Please try again.', 'plogins-shortlist'),
            'variationRequired' => $this->message('variation_required_text', 'variation_required'),
        ];

        $inline = sprintf(
            'window.shortlistWishlist=Object.assign(window.shortlistWishlist||{},%s);',
            wp_json_encode($strings),
        );

        wp_add_inline_script('shortlist', $inline, 'before');
    }

    /**
     * On variable product pages, load WooCommerce's variation script so the wishlist
     * button can track the selected variation ID.
     */
    public function enqueueVariationDependency(): void
    {
        if (! wp_script_is('shortlist', 'enqueued') || ! is_product()) {
            return;
        }

        global $product;

        if (! $product instanceof \WC_Product || ! $product->is_type('variable')) {
            return;
        }

        wp_enqueue_script('wc-add-to-cart-variation');
    }

    /**
     * Assets for output the page gate cannot see (header shortcode, block
     * grid in a template), with this plugin's strings merged in.
     */
    private function ensureAssets(): void
    {
        if (! $this->engine instanceof WishlistEngine) {
            return;
        }

        $this->engine->ensureAssets();
        $this->localizeStrings();
    }

    /**
     * `[shortlist_count]`: a header link to the wishlist with a count badge.
     *
     * The number is never printed here. A cached page would hand one shopper's
     * count to everyone, so the badge is filled by wishlist.js from
     * {@see handleCount()} and kept current after every toggle.
     */
    public function renderCountShortcode(): string
    {
        if (! $this->isEnabled()) {
            return '';
        }

        $this->ensureAssets();

        $settings = $this->settings();
        $pageId   = (int) ($settings['wishlist_page_id'] ?? 0);
        $url      = $pageId > 0 && get_post_status($pageId) === 'publish'
            ? (string) get_permalink($pageId)
            : wc_get_account_endpoint_url('shortlist');
        $label    = (string) ($settings['account_label'] ?? '');

        return sprintf(
            '<a class="shortlist-count" href="%1$s" aria-label="%2$s" data-shortlist-count>'
            . '<svg class="shortlist-count__icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24"><path d="M12 20.5s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.5 2.7c0 5.6-7.5 10.2-7.5 10.2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>'
            . '<span class="shortlist-count__badge" aria-hidden="true" data-shortlist-count-value hidden></span>'
            . '</a>',
            esc_url($url),
            esc_attr($label),
        );
    }

    /**
     * Read-only count and saved ids for the current visitor. No nonce: it
     * answers only about the caller's own cookie or account, and a nonce
     * printed into a cached page would be stale for the next visitor anyway.
     */
    public function handleCount(): void
    {
        if (! $this->engine instanceof WishlistEngine || ! $this->isFeatureEnabled()) {
            wp_send_json_error([], 404);
        }

        $count = $this->engine->getCount();
        $label = (string) ($this->settings()['account_label'] ?? '');

        wp_send_json_success([
            'count' => $count,
            'ids'   => $this->engine->getProductIds(),
            'label' => sprintf(
                /* translators: 1: wishlist label, e.g. "Wishlist", 2: number of saved items */
                _n('%1$s, %2$d item', '%1$s, %2$d items', $count, 'plogins-shortlist'),
                $label,
                $count,
            ),
        ]);
    }

    /**
     * Add the wishlist toggle after a Product Collection card's add-to-cart
     * button. Same template, settings and dedupe as the classic loop button.
     *
     * @param array<string, mixed> $block
     */
    public function appendBlockButton(string $content, array $block, mixed $instance = null): string
    {
        if (! $this->engine instanceof WishlistEngine) {
            return $content;
        }

        $postId = $instance instanceof \WP_Block ? (int) ($instance->context['postId'] ?? 0) : 0;
        $product = $postId > 0 ? wc_get_product($postId) : null;

        if (! $product instanceof \WC_Product) {
            return $content;
        }

        $button = $this->engine->renderLoopButtonHtml($product);

        if ($button === '') {
            return $content;
        }

        $this->ensureAssets();

        return $content . $button;
    }

    /**
     * Optional: a product added to the cart leaves the wishlist.
     *
     * Rows hold the id the shopper saved: the variation id for a variable
     * product (the parent cannot be saved), so that is the one removed. With
     * Shortlist PRO this touches only the default list, which is this table;
     * named PRO lists live in their own table and keep the product.
     *
     * @param mixed $cartItemKey
     * @param mixed $productId
     * @param mixed $quantity
     * @param mixed $variationId
     */
    public function removeOnAddToCart($cartItemKey, $productId, $quantity = 1, $variationId = 0): void
    {
        unset($cartItemKey, $quantity);

        if (! $this->engine instanceof WishlistEngine || ! $this->isFeatureEnabled()
            || empty($this->settings()['remove_on_add_to_cart'])) {
            return;
        }

        $variationId = absint($variationId);
        $this->engine->removeProduct($variationId > 0 ? $variationId : absint($productId));
    }

    /**
     * Same as {@see removeOnAddToCart()} for a quantity raised on a line that
     * is already in the cart.
     *
     * @param mixed $cartItemKey
     * @param mixed $quantity
     * @param mixed $oldQuantity
     * @param mixed $cart
     */
    public function removeOnQuantityIncrease($cartItemKey, $quantity, $oldQuantity = 0, $cart = null): void
    {
        if (! $cart instanceof \WC_Cart || (float) $quantity <= (float) $oldQuantity) {
            return;
        }

        $item = $cart->get_cart_item((string) $cartItemKey);

        if ($item === []) {
            return;
        }

        $this->removeOnAddToCart($cartItemKey, $item['product_id'] ?? 0, $quantity, $item['variation_id'] ?? 0);
    }

    /**
     * `[shortlist]` shortcode: renders the current visitor's wishlist body.
     */
    public function renderShortcode(): string
    {
        if (! $this->engine instanceof WishlistEngine) {
            return '';
        }

        return $this->engine->renderWishlist();
    }

    /**
     * Public wishlist HTML for the shortcode, dedicated page injection, and blocks.
     */
    public function renderWishlistHtml(): string
    {
        return $this->renderShortcode();
    }

    public function isEnabled(): bool
    {
        return $this->engine instanceof WishlistEngine && $this->isFeatureEnabled();
    }

    /**
     * Resolve a settings-backed string with label fallback (for front-end copy).
     */
    public function message(string $settingsKey, string $labelKey): string
    {
        $settings = $this->settings();
        $value = $settings[$settingsKey] ?? null;

        if (is_string($value) && $value !== '') {
            return $value;
        }

        $labels = [
            'variation_required' => __('Choose product options before adding to your wishlist.', 'plogins-shortlist'),
        ];

        return $labels[$labelKey] ?? '';
    }

    private function isFeatureEnabled(): bool
    {
        return (bool) ($this->settings()['enabled'] ?? false);
    }

    /**
     * Echo a packaged button template (loop / single).
     *
     * @param array<string, mixed> $context
     */
    private function renderTemplate(string $template, array $context): void
    {
        $file = SHORTLIST_DIR . 'templates/' . $template . '.php';

        if (! is_readable($file)) {
            return;
        }

        $shortlist_button   = isset($context['button']) && is_array($context['button']) ? $context['button'] : [];
        $shortlist_settings = isset($context['settings']) && is_array($context['settings']) ? $context['settings'] : [];

        require $file;
    }

    /**
     * Build the My Account / shortcode list HTML.
     *
     * @param array<string, mixed> $context
     */
    private function renderAccount(string $template, array $context): string
    {
        $file = SHORTLIST_DIR . 'templates/' . $template . '.php';

        if (! is_readable($file)) {
            return '';
        }

        /** @var list<\WC_Product> $shortlist_products */
        $shortlist_products = isset($context['products']) && is_array($context['products']) ? $context['products'] : [];
        $shortlist_settings = isset($context['settings']) && is_array($context['settings']) ? $context['settings'] : [];

        ob_start();
        require $file;

        return (string) ob_get_clean();
    }

    /**
     * Stored settings merged over packaged defaults.
     *
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        $stored = get_option(self::OPTION, []);

        if (! is_array($stored)) {
            $stored = [];
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require SHORTLIST_DIR . 'config/defaults.php';

        // Texts::apply() only fills what the merchant left empty, and only here,
        // on the way to being shown. The admin screen deliberately reads the raw
        // values, so saving cannot freeze one language into the option.
        return Texts::apply(array_merge($defaults, $stored));
    }

    /**
     * Render Quotlet (Request a Quote) action button under the wishlist items
     * when Quotlet is active on the site.
     *
     * @param list<\WC_Product>    $products
     * @param array<string, mixed> $settings
     */
    public function renderQuoteIntegration(array $products, array $settings): void
    {
        if (empty($products) || ! class_exists('\Estimate\Plugin')) {
            return;
        }

        if (empty($settings['enable_quote_integration'])) {
            return;
        }
        ?>
        <div class="shortlist-wishlist-actions shortlist-wishlist-actions--quote" style="margin-top:1.5rem;text-align:right;">
            <form method="post" action="">
                <?php wp_nonce_field('shortlist_quote_request_action', 'shortlist_quote_nonce'); ?>
                <input type="hidden" name="shortlist_action" value="request_quote" />
                <button type="submit" class="button alt shortlist-quote-button">
                    <?php esc_html_e('Request a quote for wishlist', 'plogins-shortlist'); ?>
                </button>
            </form>
        </div>
        <?php
    }

    /**
     * Render Waitlist notification link on out-of-stock items when Waitlist is active.
     *
     * @param \WC_Product          $product
     * @param array<string, mixed> $settings
     */
    public function renderWaitlistIntegration(\WC_Product $product, array $settings): void
    {
        if ($product->is_in_stock() || ! class_exists('\Waitlist\Plugin')) {
            return;
        }

        if (empty($settings['enable_waitlist_integration'])) {
            return;
        }

        $url = $product->get_permalink();
        if (! is_string($url) || '' === $url) {
            return;
        }
        ?>
        <a href="<?php echo esc_url($url); ?>#waitlist" class="button shortlist-waitlist-button">
            <?php esc_html_e('Notify when available', 'plogins-shortlist'); ?>
        </a>
        <?php
    }

    /**
     * Handle the POST request to transfer wishlist products into Quotlet's quote list.
     */
    public function maybeHandleQuoteRequest(): void
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
        if ('POST' !== $method) {
            return;
        }

        if (! isset($_POST['shortlist_action']) || 'request_quote' !== $_POST['shortlist_action']) {
            return;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['shortlist_quote_nonce'] ?? ''));
        if (! wp_verify_nonce($nonce, 'shortlist_quote_request_action')) {
            return;
        }

        if (! class_exists('\Estimate\Plugin') || ! $this->engine instanceof WishlistEngine) {
            return;
        }

        try {
            /** @var \Estimate\Service\QuoteList $quoteList */
            $quoteList = \Estimate\Plugin::instance()->container()->get(\Estimate\Service\QuoteList::class);
            /** @var \Estimate\Service\QuoteProducts $quoteProducts */
            $quoteProducts = \Estimate\Plugin::instance()->container()->get(\Estimate\Service\QuoteProducts::class);

            $productIds = $this->engine->getProductIds();
            foreach ($productIds as $id) {
                if ($id > 0) {
                    $quoteList->add($id, 1);
                }
            }

            $redirectUrl = $quoteProducts->quotePageUrl();
            wp_safe_redirect($redirectUrl);
            exit;
        } catch (\Throwable $e) {
            return;
        }
    }
}
