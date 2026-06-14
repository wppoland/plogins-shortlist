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

    private ?WishlistEngine $engine = null;

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
                'add'            => __('Add to wishlist', 'shortlist'),
                'remove'         => __('Remove from wishlist', 'shortlist'),
                'account'        => __('Wishlist', 'shortlist'),
                'login_required' => __('Please log in to use your wishlist.', 'shortlist'),
                'not_found'      => __('Product not found.', 'shortlist'),
            ],
            isEnabled: fn (): bool => $this->isEnabled(),
            settings: fn (): array => $this->settings(),
            renderTemplate: function (string $template, array $context): void {
                $this->renderTemplate($template, $context);
            },
            renderAccount: fn (string $template, array $context): string => $this->renderAccount($template, $context),
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

    private function isEnabled(): bool
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

        return array_merge($defaults, $stored);
    }
}
