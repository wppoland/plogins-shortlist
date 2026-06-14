# Shortlist - Wishlist for WooCommerce

Shortlist adds an accessible "Add to wishlist" button to your WooCommerce shop and product pages so visitors can save products and come back to them later from a My Account tab or the `[shortlist]` shortcode.

## Features

- AJAX add/remove toggle that updates in place with no full page reload.
- Wishlists for both guests (kept in a cookie, merged into the account on login) and logged-in customers (stored in a custom table).
- Placements for the single product page, shop and archive loops, a My Account "Wishlist" tab, the `[shortlist]` shortcode, and a block editor block.
- Vanilla JavaScript front end with no jQuery, deferred and loaded in the footer.
- No layout shift — the toggle button reserves its space when switching states.
- Settings page to control placement, labels, the saved-item count, and the wishlist list layout.

## Installation

1. Upload the plugin to `/wp-content/plugins/shortlist`, or install it via Plugins → Add New.
2. Activate it. WooCommerce must be active.
3. Open the **Shortlist** menu in wp-admin to configure placement and labels.

## Frequently Asked Questions

**Does it require WooCommerce?**
Yes. Shortlist needs an active WooCommerce installation.

**Can guests use the wishlist?**
Yes, if you allow it in settings. A guest's list lives in a cookie and is merged into their account the next time they log in.

Built by WPPoland — https://plogins.com

License: GPL-2.0-or-later
