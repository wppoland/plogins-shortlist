<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-shortlist-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Shortlist Pro',
    'url'        => 'https://plogins.com/plogins-shortlist-pro/pricing/',
    'sellable'   => true,
    'price_from' => 29,
    'currency'   => 'EUR',
    'price_pln'  => 129,
    'lead'       => [
        'en' => 'The full Shortlist PRO roadmap ships in the current release.',
        'pl' => 'Wszystkie funkcje PRO z roadmapy Shortlist są dostępne w bieżącym wydaniu.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Multiple named lists', 'desc' => 'The [shortlist_lists] shortcode for create, switch and delete.'],
            'pl' => ['title' => 'Wiele nazwanych list', 'desc' => 'Shortcode [shortlist_lists]: tworzenie, przełączanie i usuwanie list.'],
        ],
        [
            'en' => ['title' => 'Share links', 'desc' => 'The [shortlist_share] shortcode for the active wishlist.'],
            'pl' => ['title' => 'Udostępnianie listy', 'desc' => 'Shortcode [shortlist_share], publiczny link do aktywnej listy.'],
        ],
        [
            'en' => ['title' => 'Wishlist analytics', 'desc' => 'Shortlist → Wishlist Analytics with CSV export.'],
            'pl' => ['title' => 'Analityka listy życzeń', 'desc' => 'Shortlist → Wishlist Analytics + eksport CSV.'],
        ],
        [
            'en' => ['title' => 'Price alerts', 'desc' => '[shortlist_price_alerts] + Shortlist → Price Alerts: email on price drops or back-in-stock.'],
            'pl' => ['title' => 'Alerty cenowe', 'desc' => 'Shortcode [shortlist_price_alerts] + Shortlist → Price Alerts: e-mail przy spadku ceny lub powrocie na magazyn.'],
        ],
    ],
];
