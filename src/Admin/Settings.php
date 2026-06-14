<?php

declare(strict_types=1);

namespace Shortlist\Admin;

defined('ABSPATH') || exit;

use Shortlist\Contract\HasHooks;

/**
 * Admin settings page registered as a top-level "Shortlist" menu.
 *
 * Stores settings in the `shortlist_settings` option (array): the master toggle,
 * guest access, where the add-to-wishlist button renders, the toggle button
 * labels, and the My Account / shortcode list appearance (columns, which product
 * details to show, and the editable list strings). All output is escaped; all
 * input is sanitised on save. The save capability is aligned to
 * `manage_woocommerce` so shop managers can save.
 */
final class Settings implements HasHooks
{
    private const OPTION = 'shortlist_settings';
    private const PAGE   = 'shortlist-settings';

    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addMenuPage(): void
    {
        add_menu_page(
            __('Shortlist Settings', 'shortlist'),
            __('Shortlist', 'shortlist'),
            'manage_woocommerce',
            self::PAGE,
            [$this, 'renderPage'],
            'dashicons-heart',
            58,
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            self::PAGE,
            self::OPTION,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
            ],
        );

        // The menu uses manage_woocommerce; align the options.php save capability
        // so shop managers (not just admins with manage_options) can save.
        add_filter(
            'option_page_capability_' . self::PAGE,
            static fn (): string => 'manage_woocommerce',
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            return;
        }

        $settings = $this->settings();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields(self::PAGE); ?>

                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable wishlist', 'shortlist'); ?></th>
                            <td>
                                <label for="shortlist_enabled">
                                    <input
                                        type="checkbox"
                                        id="shortlist_enabled"
                                        name="<?php echo esc_attr(self::OPTION); ?>[enabled]"
                                        value="1"
                                        <?php checked((bool) ($settings['enabled'] ?? false), true); ?>
                                    />
                                    <?php esc_html_e('Let visitors add products to a wishlist.', 'shortlist'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Allow guests', 'shortlist'); ?></th>
                            <td>
                                <label for="shortlist_allow_guests">
                                    <input
                                        type="checkbox"
                                        id="shortlist_allow_guests"
                                        name="<?php echo esc_attr(self::OPTION); ?>[allow_guests]"
                                        value="1"
                                        <?php checked((bool) ($settings['allow_guests'] ?? true), true); ?>
                                    />
                                    <?php esc_html_e('Allow logged-out visitors to build a wishlist (kept in a cookie, merged on login).', 'shortlist'); ?>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Button placement', 'shortlist'); ?></h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <?php
                        $this->checkboxRow('show_on_single', __('Single product page', 'shortlist'), __('Show the add-to-wishlist button on the product page.', 'shortlist'), $settings);
                        $this->checkboxRow('show_on_loop', __('Shop and archive loops', 'shortlist'), __('Show the add-to-wishlist button on product cards in the shop loop.', 'shortlist'), $settings);
                        $this->checkboxRow('show_in_account', __('My Account menu', 'shortlist'), __('Add a "Wishlist" tab to the WooCommerce My Account area.', 'shortlist'), $settings);
                        $this->checkboxRow('show_account_count', __('Show item count', 'shortlist'), __('Show the number of saved items next to the My Account "Wishlist" menu label.', 'shortlist'), $settings);
                        ?>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Button labels', 'shortlist'); ?></h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="shortlist_button_add_text"><?php esc_html_e('Add label', 'shortlist'); ?></label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="shortlist_button_add_text"
                                    name="<?php echo esc_attr(self::OPTION); ?>[button_add_text]"
                                    value="<?php echo esc_attr((string) ($settings['button_add_text'] ?? '')); ?>"
                                    class="regular-text"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="shortlist_button_remove_text"><?php esc_html_e('Remove label', 'shortlist'); ?></label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="shortlist_button_remove_text"
                                    name="<?php echo esc_attr(self::OPTION); ?>[button_remove_text]"
                                    value="<?php echo esc_attr((string) ($settings['button_remove_text'] ?? '')); ?>"
                                    class="regular-text"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Wishlist list', 'shortlist'); ?></h2>
                <p class="description">
                    <?php esc_html_e('Controls the list shown on the My Account tab and via the [shortlist] shortcode and the Shortlist block.', 'shortlist'); ?>
                </p>
                <table class="form-table" role="presentation">
                    <tbody>
                        <?php
                        $this->textRow('account_title', __('List heading', 'shortlist'), __('My wishlist', 'shortlist'), $settings);
                        $this->textRow('account_intro_text', __('Intro text', 'shortlist'), '', $settings);
                        $this->textRow('empty_text', __('Empty-list message', 'shortlist'), __('Your wishlist is empty.', 'shortlist'), $settings);
                        ?>
                        <tr>
                            <th scope="row">
                                <label for="shortlist_grid_columns"><?php esc_html_e('Columns', 'shortlist'); ?></label>
                            </th>
                            <td>
                                <input
                                    type="number"
                                    id="shortlist_grid_columns"
                                    name="<?php echo esc_attr(self::OPTION); ?>[grid_columns]"
                                    value="<?php echo esc_attr((string) ($settings['grid_columns'] ?? 3)); ?>"
                                    min="1"
                                    max="6"
                                    step="1"
                                    class="small-text"
                                />
                                <p class="description"><?php esc_html_e('Number of product columns in the wishlist grid (1-6).', 'shortlist'); ?></p>
                            </td>
                        </tr>
                        <?php
                        $this->checkboxRow('show_list_title', __('Show heading', 'shortlist'), __('Show the list heading above the products.', 'shortlist'), $settings);
                        $this->checkboxRow('show_product_image', __('Show image', 'shortlist'), __('Show the product thumbnail.', 'shortlist'), $settings);
                        $this->checkboxRow('show_product_name', __('Show name', 'shortlist'), __('Show the product name.', 'shortlist'), $settings);
                        $this->checkboxRow('show_price', __('Show price', 'shortlist'), __('Show the product price.', 'shortlist'), $settings);
                        $this->checkboxRow('show_add_to_cart', __('Show add-to-cart', 'shortlist'), __('Show an add-to-cart button for in-stock products.', 'shortlist'), $settings);
                        $this->checkboxRow('show_remove_button', __('Show remove button', 'shortlist'), __('Show a remove-from-wishlist button on each item.', 'shortlist'), $settings);
                        ?>
                    </tbody>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render a single checkbox row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function checkboxRow(string $key, string $label, string $help, array $settings): void
    {
        $id = 'shortlist_' . $key;
        ?>
        <tr>
            <th scope="row"><?php echo esc_html($label); ?></th>
            <td>
                <label for="<?php echo esc_attr($id); ?>">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr($id); ?>"
                        name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                        value="1"
                        <?php checked((bool) ($settings[$key] ?? false), true); ?>
                    />
                    <?php echo esc_html($help); ?>
                </label>
            </td>
        </tr>
        <?php
    }

    /**
     * Render a single text-input row in the form-table.
     *
     * @param array<string, mixed> $settings
     */
    private function textRow(string $key, string $label, string $placeholder, array $settings): void
    {
        $id = 'shortlist_' . $key;
        ?>
        <tr>
            <th scope="row">
                <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></label>
            </th>
            <td>
                <input
                    type="text"
                    id="<?php echo esc_attr($id); ?>"
                    name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]"
                    value="<?php echo esc_attr((string) ($settings[$key] ?? '')); ?>"
                    placeholder="<?php echo esc_attr($placeholder); ?>"
                    class="regular-text"
                />
            </td>
        </tr>
        <?php
    }

    /**
     * Sanitises the submitted settings before save, preserving defaults for any
     * field not on the form.
     *
     * @param mixed $raw
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            $raw = [];
        }

        $defaults = $this->settings();

        $addText    = isset($raw['button_add_text']) ? sanitize_text_field((string) $raw['button_add_text']) : '';
        $removeText = isset($raw['button_remove_text']) ? sanitize_text_field((string) $raw['button_remove_text']) : '';

        $columns = isset($raw['grid_columns']) ? (int) $raw['grid_columns'] : (int) ($defaults['grid_columns'] ?? 3);
        $columns = max(1, min(6, $columns));

        return array_merge($defaults, [
            'enabled'            => ! empty($raw['enabled']),
            'allow_guests'       => ! empty($raw['allow_guests']),
            'show_on_single'     => ! empty($raw['show_on_single']),
            'show_on_loop'       => ! empty($raw['show_on_loop']),
            'show_in_account'    => ! empty($raw['show_in_account']),
            'show_account_count' => ! empty($raw['show_account_count']),
            'button_add_text'    => $addText !== '' ? $addText : (string) ($defaults['button_add_text'] ?? __('Add to wishlist', 'shortlist')),
            'button_remove_text' => $removeText !== '' ? $removeText : (string) ($defaults['button_remove_text'] ?? __('Remove from wishlist', 'shortlist')),
            'account_title'      => isset($raw['account_title']) ? sanitize_text_field((string) $raw['account_title']) : (string) ($defaults['account_title'] ?? ''),
            'account_intro_text' => isset($raw['account_intro_text']) ? sanitize_text_field((string) $raw['account_intro_text']) : (string) ($defaults['account_intro_text'] ?? ''),
            'empty_text'         => isset($raw['empty_text']) ? sanitize_text_field((string) $raw['empty_text']) : (string) ($defaults['empty_text'] ?? ''),
            'grid_columns'       => $columns,
            'show_list_title'    => ! empty($raw['show_list_title']),
            'show_product_image' => ! empty($raw['show_product_image']),
            'show_product_name'  => ! empty($raw['show_product_name']),
            'show_price'         => ! empty($raw['show_price']),
            'show_add_to_cart'   => ! empty($raw['show_add_to_cart']),
            'show_remove_button' => ! empty($raw['show_remove_button']),
        ]);
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
