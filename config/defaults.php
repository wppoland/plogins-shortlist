<?php
/**
 * Default settings, merged under the option key `shortlist_settings`.
 *
 * The feature ships enabled. The merchant tunes the button labels, where the
 * add-to-wishlist button renders (single product page and/or shop loop), and the
 * My Account / shortcode list appearance. All wishlist logic lives in the
 * storefront-kit WishlistEngine; these values are passed through to it as the
 * resolved settings.
 *
 * @package Shortlist
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    'enabled' => true,

    // Who can build a wishlist.
    'allow_guests' => true,

    // Where the add-to-wishlist button appears.
    'show_on_single'  => true,
    'show_on_loop'    => true,
    'show_in_account' => true,

    // Show the saved-item count next to the My Account "Wishlist" menu label.
    'show_account_count' => true,

    // Every customer-facing string below is empty on purpose. A value here is
    // written into the option at activation and can never be translated, because
    // a config array is not a gettext call, so the packaged text used to survive
    // even a complete language pack. Empty means "use Shortlist\Service\Texts",
    // which is translated; anything a merchant types still wins.
    //
    // Button labels (toggle state).
    'button_add_text'    => '',
    'button_remove_text' => '',

    // My Account / shortcode list.
    'account_label'      => '',
    'account_title'      => '',
    'account_intro_text' => '',
    'empty_text'         => '',
    'grid_columns'       => 3,
    'show_list_title'    => true,
    'show_product_image' => true,
    'show_product_name'  => true,
    'show_price'         => true,
    'show_add_to_cart'   => true,
    'show_remove_button' => true,

    // Runtime strings (front-end script / AJAX handler).
    'login_required_text'    => '',
    'product_not_found_text' => '',
    'variation_required_text' => '',

    // Dedicated wishlist page (optional).
    'wishlist_page_id'        => 0,
    'inject_wishlist_on_page' => true,
];
