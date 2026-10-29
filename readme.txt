=== Shortlist - Wishlist for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, wishlist, product wishlist, save for later, favourites
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.1.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Wishlist for WooCommerce that works without an account: save from any listing, keep the list on login, My Account tab, shortcode and block.

== Description ==

Shortlist adds an "Add to wishlist" button to your WooCommerce shop loop and product pages. Shoppers save products, favourites and save-for-later items, then come back from a "Wishlist" tab in My Account, a page of their own, or anywhere you drop the `[shortlist]` shortcode.

Guests can save products before they log in. A guest list lives in a cookie; the next time that visitor signs in, their saved items move onto their account, so nothing is lost at the login step. Logged-in customers' lists are stored in a custom database table keyed to their user id.

The plugin is written for stores that care about front-end weight and accessibility:

* The front-end script is vanilla JavaScript with no jQuery dependency. It is deferred and loaded in the footer.
* The toggle button reserves its space, so switching between the add and remove states does not reflow the page (no CLS).
* The toggle is a real `<button>` with `aria-pressed`. When a product appears more than once on a page, every button for it updates together after a save, and the change is announced to screen readers through a polite live region.
* Saving and removing happen over admin-ajax with no page reload.

On variable products the button follows the selected variation, so a customer saves the exact size or colour they chose rather than the parent product. Until they pick options the button stays disabled, with a hint you can word yourself.

The source lives on GitHub at [github.com/wppoland/plogins-shortlist](https://github.com/wppoland/plogins-shortlist), that's the place for bug reports and patches.

= Documentation and links =

* **Documentation**: [plogins.com/plogins-shortlist/docs/](https://plogins.com/plogins-shortlist/docs/)
* **Plugin page**: [plogins.com/plogins-shortlist/](https://plogins.com/plogins-shortlist/)
* **Source code**: [github.com/wppoland/plogins-shortlist](https://github.com/wppoland/plogins-shortlist)
* **Bug reports and feature requests**: [github.com/wppoland/plogins-shortlist/issues](https://github.com/wppoland/plogins-shortlist/issues)
* **Shortlist Pro**: [plogins.com/plogins-shortlist-pro/](https://plogins.com/plogins-shortlist-pro/)


= Where the button and list can appear =

* The single product page, below the add-to-cart area.
* Product cards in the shop, category and tag loops.
* Product cards in block-theme Product Collection grids (for example a homepage grid in Twenty Twenty-Five), next to each card's add-to-cart button.
* A header counter, via the `[shortlist_count]` shortcode: a heart link to the wishlist with a live count badge. In a block theme, put it in the header with the Shortcode block.
* A "Wishlist" tab in WooCommerce My Account, optionally showing a saved-item count like "Wishlist (3)".
* A dedicated page you pick or create from the settings screen.
* Any post or page, via the `[shortlist]` shortcode.
* The block editor, via the **Shortlist Wishlist** block (server-rendered, so the editor preview matches the front end).
* The Elementor editor, via the **Wishlist** widget (same list as the shortcode; loads only when Elementor is active).

Each placement is a separate switch on the settings screen.

= Settings =

The Shortlist menu in wp-admin opens to shop managers (it uses the `manage_woocommerce` capability), not only administrators. From there you can:

* Turn the wishlist on or off, and decide whether guests may use it.
* Choose where the button shows up: single product, shop loop, My Account, and a dedicated page.
* Show or hide the saved-item count on the My Account menu.
* Remove a product from the wishlist when the shopper adds it to the cart (off by default).
* Set the add and remove button labels, and the variation hint.
* Shape the list itself: heading, intro and empty-list text, how many columns the grid uses, and which details (image, name, price, add-to-cart, remove button) each saved product shows.

Every setting has a "?" next to it that opens a short explanation of what it does.

Shortlist only loads its stylesheet and script on the pages where the wishlist actually appears, so the rest of your store stays untouched.

= Works with other Plogins plugins =

* [Quotlet - Request a Quote for WooCommerce](https://plogins.com/quotlet/) - a "Request a quote for these items" button under the wishlist adds the saved quote products to a quote request. It follows Quotlet's own on/off switch.
* [Waitlist - Back in Stock for WooCommerce](https://plogins.com/plogins-waitlist/) - out-of-stock simple products on the wishlist show Waitlist's sign-up form right there; out-of-stock variable products link to their page. Switch: "Show the waitlist form on out-of-stock items on the wishlist", on by default.
* [Versus - Product Compare for WooCommerce](https://plogins.com/plogins-versus/) - with two or more saved products, a "Compare these" button opens them in the comparison table.

== Shortlist Pro ==

Shortlist Pro is a separate paid plugin that extends Shortlist.

Included in Shortlist:

* Wishlist button
* My Account tab
* [shortlist] shortcode
* Guest support
* AJAX add and remove

Shortlist Pro adds:

* Multiple named lists
* Share links
* Wishlist analytics
* Price alerts

The free edition is not a trial and its features stay available without a time limit.

* **Shortlist Pro** - [plogins.com/plogins-shortlist-pro/](https://plogins.com/plogins-shortlist-pro/)
* **Compare editions and pricing** - [plogins.com/plogins-shortlist-pro/pricing/](https://plogins.com/plogins-shortlist-pro/pricing/)
* **PRO documentation** - [plogins.com/plogins-shortlist-pro/docs/](https://plogins.com/plogins-shortlist-pro/docs/)

== Installation ==

1. Upload the plugin to `/wp-content/plugins/shortlist`, or install via Plugins > Add New.
2. Activate it. WooCommerce must be active.
3. Visit the **Shortlist** menu in wp-admin to configure placement and labels.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Shortlist requires an active WooCommerce installation.

= Can guests use the wishlist? =

Yes, if you allow it in settings. A guest's list lives in a cookie and is merged into their account the next time they log in.

= Does it use jQuery? =

No. The plugin's own front-end script is vanilla JavaScript with no jQuery dependency.

= How do I show the wishlist on a page? =

Use the `[shortlist]` shortcode, or rely on the "Wishlist" tab added to the WooCommerce My Account area.

= Does it work with variable products? =

Yes. On variable products the wishlist button follows the selected variation, so the saved item can include the chosen size or colour.

= How do I show a wishlist counter in the header? =

Add the `[shortlist_count]` shortcode to your header: in a block theme, edit the Header template part and insert a Shortcode block. It prints a heart link to your wishlist page (or the My Account wishlist tab) with a badge showing how many items are saved. Screen readers hear "Wishlist, 3 items". The number is fetched by the browser after the page loads and updates as items are added or removed, so it stays correct on cached pages.

= Can I remove a product from the wishlist once it is in the cart? =

Yes. Turn on "Remove when added to cart" in the General settings. When a shopper adds a saved product to the cart, it leaves their wishlist, and the counter and buttons on the page update. For a variable product, the saved variation is the one removed. It is off by default.

= Can I create a dedicated wishlist page? =

Yes. Choose an existing page or create one from the Shortlist settings screen. The plugin can inject the `[shortlist]` list automatically.

= Is the wishlist accessible? =

Yes. The wishlist button is a real button with `aria-pressed`, screen-reader announcements and no layout shift when the saved state changes.


= Does it work with Quotlet, Waitlist and Versus? =

Yes. Quotlet adds a quote button for the saved quote products, Waitlist shows its sign-up form on out-of-stock items, and Versus adds a "Compare these" button when two or more products are saved. Only the Waitlist form has a switch, under Ecosystem integrations in the Shortlist settings.

= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The Wishlist tab in a customer's My Account, with its item count and the saved products, each with add-to-cart and remove buttons.
2. The wishlist page on a phone.
3. The Shortlist settings screen.
4. The header wishlist counter from the `[shortlist_count]` shortcode, showing three saved products.

== External Services ==

Shortlist does not connect to any external service. Saving and removing items happens over your own site's admin-ajax endpoint, and all wishlist data stays in your WordPress database: logged-in customers' lists live in a custom `shortlist_items` table keyed to their user id, guest lists live in a cookie on the visitor's own browser until they log in, and settings are stored in the `shortlist_settings` option. The plugin sends no email and loads no third-party fonts, scripts or trackers.

== Translations ==

Plogins Shortlist is fully translatable and ships the `plogins-shortlist.pot` template. Translations are delivered by WordPress.org language packs from translate.wordpress.org, which is where Polish, German and Spanish are being contributed; the package itself carries no compiled translation files.

== Changelog ==

= 1.1.5 =
* Fixed: the site could fatal ("Unknown named parameter") when another Plogins plugin with an older copy of the shared library was active. The plugin's bundled copy now lives under its own namespace.

= 1.1.4 =
* Changed: the wishlist-to-quote button moved into Quotlet, which shows it only while its quote requests are on and only for the saved quote products, so the Quotlet switch left the settings here. With Waitlist active, an out-of-stock item on the wishlist shows the Waitlist sign-up form itself instead of a link to a #waitlist anchor that did not exist; a variable product links to its page, where the variation is picked. The Quotlet and Waitlist lines under Ecosystem integrations link to the plugin search on the Add Plugins screen when the plugin is not installed.
* Fixed: a wishlist page that holds the [shortlist] shortcode and is also chosen as the Wishlist page (the page Create page makes) showed the wishlist twice, with every button under it doubled. It now shows once; a chosen page without the shortcode still gets the list added.

= 1.1.3 =
* New: with Quotlet active, the wishlist page can show a Request a quote for wishlist button that copies the saved items into the quote list and opens the quote page. With Waitlist active, out-of-stock items in the wishlist show a Notify when available link to the product page. Both can be switched off under Ecosystem integrations in the settings.

= 1.1.2 =
* The Upgrade to PRO links open the pricing page in the admin's language where plogins.com has one, and tell plogins.com which plugin the visit came from (utm_source, utm_medium and utm_campaign in the link). Nothing is sent from your site.
* Readme: the PRO section lists what the free plugin includes and what the paid edition adds as two lists, which WordPress.org can display (it does not display tables).

= 1.1.1 =
* Readme only: a PRO section with links to the paid edition.

= 1.1.0 =
* New: `[shortlist_count]` shortcode for the header, a heart link to the wishlist with a count badge and a spoken label such as "Wishlist, 3 items". The count is loaded in the browser and updates after every add or remove, so a cached page never shows another shopper's number.
* New: the add-to-wishlist button on block-theme Product Collection grids, next to each card's add-to-cart button. On a block theme's shop page, where WooCommerce also runs the classic loop hook, each product still gets one button.
* New: "Remove when added to cart" setting, off by default. A saved product leaves the wishlist when it is added to the cart, including when its quantity in the cart is raised; for a variable product the saved variation is removed.
* Fixed: on Storefront and other themes that float WooCommerce product loops, the wishlist grid squeezed every card to a narrow column and left an empty first cell.

= 1.0.21 =
* Privacy: after a customer signed in, the items they had saved as a guest kept the guest cookie's id, and that cookie stays in the browser after logout. The next person at that browser saw the customer's wishlist and could remove items from it. Items handed to an account now leave the guest list, and guest lookups no longer match account items.
* Fixed: a product saved both as a guest and on the account was listed twice after signing in, and counted twice in "Wishlist (n)".
* Fixed: items saved as a guest were not moved to the account when the visitor registered, on the My Account form or at checkout, because that sign-in never fires wp_login.
* Fixed: the Shortlist Wishlist block could not be added in the block editor. Its script loaded before the editor's own scripts and stopped with an error, so the block never registered.
* Fixed: a page built with the Elementor Wishlist widget got the list without its stylesheet and script, so it was unstyled and its remove buttons did nothing.
* Fixed: switching the wishlist off left the [shortlist] shortcode, the block and the My Account wishlist URL still printing the list.

= 1.0.20 =
* The upgrade notice's "Coming soon" and "Get notified" labels are English source strings for every language; Polish sites used to get their own Polish source text, which translators in other languages then saw untranslated.

= 1.0.19 =
* Security (low): the wishlist accepted any product ID, including draft, pending and private products, and then showed that product's name, price and image on the wishlist page. A visitor could read unpublished products by sending guessed IDs. Products the visitor cannot read are now refused and are no longer listed.

= 1.0.18 =
* Display name drops the "Plogins " prefix. The slug, the text domain and every option key are unchanged, so nothing on an existing install moves.

= 1.0.17 =
* The sidebar upgrade promo now follows the same dismissal as the banner. Dismissing the banner used to leave a full-height advert on the settings screen for good, which is not what the WordPress.org guideline on upgrade prompts means by used with moderation.

= 1.0.16 =
* Fixed: the saved-item count loaded every saved product to count them. The My Account menu badge and the number returned after every add or remove asked the database for the whole list and then built a full product object per row, so a shopper with forty saved items paid forty product loads for one number, on every My Account page view. The count is now a single COUNT query and loads nothing.

= 1.0.15 =
* Changed: the listing now says a paid edition exists and what it adds. It never did, so anyone reading the WordPress.org page had no way of knowing there was one.

= 1.0.14 =
* Fixed: the PRO upgrade promo kept selling to people who had already bought the paid edition. Only the banner could be dismissed, so the sidebar promo and the locked feature cards followed a paying customer around for good. The promo now checks whether the paid edition is active and steps aside when it is.
* Fixed: arrow glyphs in the admin menu paths, and in the strings handed to translators. An arrow inside a translatable string makes the glyph every translator's problem and changes the layout in any locale that drops it.

= 1.0.13 =
* Changed: the PRO feature cards printed an arrow glyph in menu paths where the rest of the plugin and the documentation use a plain ">". Same navigation, one character that renders everywhere.

= 1.0.12 =
* Fixed: the wishlist rendered with no styling and dead buttons on any page other than the shop, a product, a product category, My Account or the page set as the wishlist page. The `[shortlist]` shortcode and the wishlist block print the full markup anywhere, but the stylesheet and script did not follow them. They now load wherever the shortcode or the block is on the page, and nowhere else.

= 1.0.11 =
* Fixed: deleting the plugin left the per-user "dismiss" flag from the PRO notice in the database. Uninstall now removes it for every user, not just the one who dismissed it.

= 1.0.10 =
* Fixed: every customer-facing label was untranslatable. The button text, the My Account title, the empty-list message and the error strings shipped as English sentences in a config file and were written into the settings option the moment the plugin activated, so a shop running in another language showed English however complete its language pack was. The settings screen already said "leave blank to use the default"; the packaged value meant blank never happened.
* The defaults are now translated strings resolved when the label is about to be shown, never written back to the database. A label you typed yourself is still used exactly as typed.
* On update, a label left byte for byte as the English default is cleared so the translated one takes over. Anything you edited, including a hand translation, is matched exactly and kept.

= 1.0.9 =
* Translations: refreshed the bundled `plogins-shortlist.pot`. It had fallen behind 1.0.8, missing six strings from the wishlist admin list and the Elementor widget and still carrying a price string that had been removed. That template is what translators work from, so those six could not be translated until now.

= 1.0.8 =
* Fixed the PRO promo on the settings screen quoting a price in PLN. PRO is priced and charged in EUR, so an admin on a Polish site was shown a zloty amount and then billed in euro, and the zloty figure was a fixed conversion that drifted from the real charge as the rate moved. The promo now shows the euro price that is actually taken.
* New: Elementor widget for the wishlist list, same output as the shortcode and block. Loads only when Elementor is active.

= 1.0.6 =
* Fixed: Save Changes on the Shortlist settings screen now saves every option again, including the whole "Wishlist list" card (heading, intro, empty-list message, columns and the show image, name, price, add-to-cart and remove toggles). Previously that button posted nothing.
* Fixed: "Create wishlist page" now only creates the page, instead of hijacking the settings save.

= 1.0.4 =
* Translations: completed Polish, German and Spanish for the PRO upgrade panel.

= 1.0.3 =
* Accessibility improvements to the admin and storefront markup.
* Fixed low-contrast admin headings under an OS dark-mode preference.

= 1.0.2 =
* Added bundled Polish, German and Spanish translations for the plugin interface.

= 1.0.1 =
* First stable release.

= 0.3.1 =
* Renamed to Plogins Shortlist for WooCommerce for a more distinctive plugin name.

= 0.3.0 =
* New: **Wishlist page**, pick an existing page or create one from settings; auto-inject the `[shortlist]` list when the page has no shortcode yet.
* New: **Variation-aware saves**, on variable products the button tracks the selected variation; configurable hint when no variation is chosen.
* Improved: settings screen groups wishlist page, variation hint and existing placement controls.

= 0.2.0 =
* Polish: refreshed, themeable storefront styles (heart icon, dark-mode, CLS-safe grid) and a modern, card-based settings screen with an accessible "?" help popover on every option.
* Accessibility: wishlist changes are now announced to screen readers, and the My Account count updates live without a page reload.
* Robustness: friendly empty-state with a "Browse products" link, clear failure messaging, and defensive guards against missing product data.
* New: **Shortlist Wishlist** block for the block editor (server-rendered, matches the `[shortlist]` shortcode).
* New: optional saved-item count next to the My Account "Wishlist" menu label.
* New: full control over the wishlist list, heading, intro and empty-list text, column count, and which product details (image, name, price, add-to-cart, remove button) appear.
* New: uninstall cleanup removes the wishlist table and plugin options on delete.
* i18n: added Domain Path and a `languages` directory for translations.

= 0.1.0 =
* Initial release: accessible AJAX wishlist for WooCommerce with guest support, a My Account tab, a shortcode, and a settings page for placement and labels.

== Upgrade Notice ==

= 1.1.0 =
Adds a header counter shortcode, wishlist buttons on block-theme product grids, and an optional "remove when added to cart" setting.

= 1.0.21 =
Privacy release: after logout, a browser could still show and edit the wishlist of the customer who had signed in on it. Update, nothing else to do.

= 1.0.19 =
Security release. Default installations are exposed: names, prices and images of draft, pending and private products could be read. Update, nothing else to do.
