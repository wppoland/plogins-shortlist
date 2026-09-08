<?php

declare(strict_types=1);

namespace Shortlist\Service;

defined('ABSPATH') || exit;

/**
 * The customer-facing strings a merchant may override, in the language of the
 * site.
 *
 * They used to be English sentences in config/defaults.php, written into
 * `shortlist_settings` at activation. A string in a config array is never
 * wrapped in a gettext call, so it is not in the .pot and cannot be translated,
 * and once it is in the option even a complete language pack cannot reach it.
 * The plugin already carried `__()` fallbacks next to those keys, which looked
 * like the problem was handled; they could never fire, because the key was
 * always present.
 *
 * The packaged default is now empty, meaning "use the string below". A merchant
 * who types their own still wins, and what they typed is stored as typed.
 */
final class Texts
{
    /**
     * Setting key => the translated default.
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'button_add_text'         => __('Add to wishlist', 'plogins-shortlist'),
            'button_remove_text'      => __('Remove from wishlist', 'plogins-shortlist'),
            'account_label'           => __('Wishlist', 'plogins-shortlist'),
            'account_title'           => __('My wishlist', 'plogins-shortlist'),
            'empty_text'              => __('Your wishlist is empty.', 'plogins-shortlist'),
            'login_required_text'     => __('Please log in to use your wishlist.', 'plogins-shortlist'),
            'product_not_found_text'  => __('Product not found.', 'plogins-shortlist'),
            'variation_required_text' => __('Choose product options before adding to your wishlist.', 'plogins-shortlist'),
        ];
    }

    /**
     * Fill every empty text key with its translated default.
     *
     * Applied on the way OUT, where the string is about to be shown, and never
     * on the way in: writing the resolved text back to the option would freeze
     * one language into the database, which is the bug this class exists to fix.
     *
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function apply(array $settings): array
    {
        foreach (self::defaults() as $key => $text) {
            if (trim((string) ($settings[$key] ?? '')) === '') {
                $settings[$key] = $text;
            }
        }

        return $settings;
    }
}
