<?php
/**
 * Shop/archive loop add-to-wishlist button.
 *
 * Rendered by the storefront-kit WishlistEngine on
 * `woocommerce_after_shop_loop_item`.
 *
 * @var array{product_id:int,in_wishlist:bool,label:string} $shortlist_button
 * @var array<string, mixed>                                $shortlist_settings
 *
 * @package Shortlist/Templates
 */

declare(strict_types=1);

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Variables are local to the template include scope.

defined('ABSPATH') || exit;
?>
<button
    type="button"
    class="button shortlist-wishlist-button shortlist-wishlist-button--loop<?php echo $shortlist_button['in_wishlist'] ? ' is-active' : ''; ?>"
    data-shortlist-wishlist-button
    data-product-id="<?php echo esc_attr((string) $shortlist_button['product_id']); ?>"
    aria-pressed="<?php echo $shortlist_button['in_wishlist'] ? 'true' : 'false'; ?>"
>
    <?php echo esc_html($shortlist_button['label']); ?>
</button>
