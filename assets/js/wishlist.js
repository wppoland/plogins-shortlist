/**
 * Shortlist - accessible WooCommerce wishlist.
 *
 * Vanilla JS, no jQuery. Event delegation so buttons rendered after page load
 * (infinite scroll, quick-view, AJAX filters) keep working. Config is provided
 * by the storefront-kit WishlistEngine via wp_localize_script as
 * `window.shortlistWishlist`. Loaded with defer/in_footer, so the DOM is ready
 * and no DOMContentLoaded wrapper is needed.
 *
 * Feedback is announced to assistive tech through a polite aria-live region and,
 * on failure, surfaced inline so the shopper is never left guessing.
 */
( function () {
	'use strict';

	var BUTTON_SELECTOR = '[data-shortlist-wishlist-button]';
	var COUNT_SELECTOR = '[data-shortlist-count]';
	var liveRegion = null;

	/**
	 * Lazily create the shared visually-hidden status region used to announce
	 * add/remove/failure outcomes to screen-reader users.
	 */
	function announce( message ) {
		if ( ! message ) {
			return;
		}

		if ( ! liveRegion ) {
			liveRegion = document.createElement( 'div' );
			liveRegion.className = 'shortlist-wishlist-status';
			liveRegion.setAttribute( 'role', 'status' );
			liveRegion.setAttribute( 'aria-live', 'polite' );
			document.body.appendChild( liveRegion );
		}

		// Clear first so identical consecutive messages are still announced.
		liveRegion.textContent = '';
		window.requestAnimationFrame( function () {
			liveRegion.textContent = message;
		} );
	}

	/**
	 * Keep the "Wishlist (n)" My Account menu label in sync after a toggle,
	 * without a page reload. Best-effort: silently no-ops if the label is absent.
	 */
	function updateMenuCount( count ) {
		if ( typeof count !== 'number' ) {
			return;
		}

		var link = document.querySelector(
			'.woocommerce-MyAccount-navigation-link--shortlist a'
		);

		if ( ! link ) {
			return;
		}

		var base = link.textContent.replace( /\s*\(\d+\)\s*$/, '' );
		link.textContent = count > 0 ? base + ' (' + count + ')' : base;
	}

	/**
	 * Fill every [shortlist_count] link: the badge number and the spoken label
	 * ("Wishlist, 3 items", pluralised by the server). The page never carries
	 * the number itself, so a cached page cannot show someone else's count.
	 */
	function renderCount( count, label ) {
		document.querySelectorAll( COUNT_SELECTOR ).forEach( function ( link ) {
			var badge = link.querySelector( '[data-shortlist-count-value]' );
			if ( badge ) {
				badge.textContent = String( count );
				badge.hidden = count < 1;
			}
			if ( label ) {
				link.setAttribute( 'aria-label', label );
			}
		} );
		updateMenuCount( count );
	}

	/**
	 * Ask the server for the visitor's own count and saved ids, then update
	 * the counters and every button on the page. Read-only, no nonce.
	 */
	function refresh() {
		var config = window.shortlistWishlist;

		if ( ! config || ! config.countAction ) {
			return;
		}

		fetch(
			config.ajaxUrl + '?action=' + encodeURIComponent( config.countAction ),
			{ credentials: 'same-origin' }
		)
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( payload ) {
				if ( ! payload || ! payload.success || ! payload.data ) {
					return;
				}
				var ids = ( payload.data.ids || [] ).map( String );
				renderCount( payload.data.count, payload.data.label );
				document.querySelectorAll( BUTTON_SELECTOR ).forEach( function ( el ) {
					var active = ids.indexOf( el.dataset.productId || '' ) !== -1;
					if ( el.classList.contains( 'is-active' ) === active ) {
						return;
					}
					el.classList.toggle( 'is-active', active );
					el.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
					var label = active ? config.removeLabel : config.addLabel;
					if ( label ) {
						el.textContent = label;
					}
				} );
			} )
			.catch( function () {} );
	}

	if ( document.querySelector( COUNT_SELECTOR ) ) {
		refresh();
	}

	/**
	 * Reflect the new saved/unsaved state across every button for this product
	 * (loop + single buttons can co-exist on one page).
	 */
	function syncButtons( productId, active, label ) {
		document
			.querySelectorAll(
				BUTTON_SELECTOR + '[data-product-id="' + productId + '"]'
			)
			.forEach( function ( el ) {
				var wasActive = el.classList.contains( 'is-active' );
				el.classList.toggle( 'is-active', active );
				el.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
				if ( label ) {
					el.textContent = label;
				}

				// Presentation-only hook: flag a fresh save so CSS can play the
				// one keepsake bloom. Self-clears; never fires on initial render
				// or on remove. No behaviour depends on it.
				if ( active && ! wasActive ) {
					el.classList.remove( 'is-just-saved' );
					// Reflow so re-adding the class restarts the animation.
					void el.offsetWidth;
					el.classList.add( 'is-just-saved' );
					window.setTimeout( function () {
						el.classList.remove( 'is-just-saved' );
					}, 600 );
				}
			} );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( BUTTON_SELECTOR );

		if ( ! button ) {
			return;
		}

		var config = window.shortlistWishlist;

		if ( ! config ) {
			return;
		}

		event.preventDefault();

		if (
			! config.allowGuests &&
			! document.body.classList.contains( 'logged-in' )
		) {
			window.location.href = config.loginUrl;
			return;
		}

        if ( button.getAttribute( 'aria-disabled' ) === 'true' ) {
            if ( config.variationRequired ) {
                announce( config.variationRequired );
            }
            return;
        }

        // Guard against double submissions while a request is in flight.
		if ( button.getAttribute( 'aria-busy' ) === 'true' ) {
			return;
		}

		var productId = button.dataset.productId || '';

		if ( ! productId ) {
			return;
		}

		var formData = new FormData();
		formData.append( 'action', config.action );
		formData.append( 'nonce', config.nonce );
		formData.append( 'product_id', productId );

		button.disabled = true;
		button.setAttribute( 'aria-busy', 'true' );

		fetch( config.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: formData,
		} )
			.then( function ( response ) {
				// Parse the body either way: WooCommerce returns a useful
				// message (e.g. "Please log in") even on 403/404 responses.
				return response.json();
			} )
			.then( function ( payload ) {
				if ( ! payload || ! payload.success || ! payload.data ) {
					var serverMessage =
						payload &&
						payload.data &&
						payload.data.message
							? payload.data.message
							: config.errorText;
					announce( serverMessage );
					return;
				}

				var active = !! payload.data.in_wishlist;
				var label = payload.data.button_text || '';

				syncButtons( productId, active, label );
				if ( document.querySelector( COUNT_SELECTOR ) ) {
					refresh();
				} else {
					updateMenuCount( payload.data.count );
				}

				announce(
					active
						? config.addedText || label
						: config.removedText || label
				);
			} )
			.catch( function () {
				// Network/parse failure: leave button state intact, tell the user.
				announce( config.errorText );
			} )
           	.finally( function () {
				if ( button.dataset.requiresVariation === '1' && ! button.classList.contains( 'is-active' ) ) {
					button.disabled = true;
					button.setAttribute( 'aria-disabled', 'true' );
				} else {
					button.disabled = false;
					button.removeAttribute( 'aria-disabled' );
				}
				button.removeAttribute( 'aria-busy' );
			} );
	} );

	// "Remove from wishlist when added to cart": the server already dropped
	// the item, so bring the page in line. Blocks dispatch a DOM event,
	// classic AJAX add-to-cart a jQuery one.
	function afterAddToCart() {
		if ( window.shortlistWishlist && window.shortlistWishlist.removeOnCart ) {
			refresh();
		}
	}

	document.body.addEventListener( 'wc-blocks_added_to_cart', afterAddToCart );

	if ( typeof jQuery === 'undefined' ) {
		return;
	}

	jQuery( document.body ).on( 'added_to_cart', afterAddToCart );

	var variationsForm = document.querySelector( 'form.variations_form' );

	if ( ! variationsForm ) {
		return;
	}

	jQuery( variationsForm ).on( 'found_variation', function ( _event, variation ) {
		if ( ! variation || ! variation.variation_id ) {
			return;
		}

		document
			.querySelectorAll(
				BUTTON_SELECTOR + '[data-requires-variation="1"]'
			)
			.forEach( function ( el ) {
				var variationId = String( variation.variation_id );
				el.dataset.productId = variationId;
				el.disabled = false;
				el.setAttribute( 'aria-disabled', 'false' );

				var hint = el
					.closest( '.shortlist-wishlist-single' )
					?.querySelector( '[data-shortlist-variation-hint]' );

				if ( hint ) {
					hint.hidden = true;
				}
			} );
	} );

	jQuery( variationsForm ).on( 'reset_data', function () {
		document
			.querySelectorAll(
				BUTTON_SELECTOR + '[data-requires-variation="1"]'
			)
			.forEach( function ( el ) {
				el.disabled = true;
				el.setAttribute( 'aria-disabled', 'true' );
				el.classList.remove( 'is-active' );
				el.setAttribute( 'aria-pressed', 'false' );

				var hint = el
					.closest( '.shortlist-wishlist-single' )
					?.querySelector( '[data-shortlist-variation-hint]' );

				if ( hint ) {
					hint.hidden = false;
				}
			} );
	} );
} )();
