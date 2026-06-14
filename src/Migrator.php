<?php

declare(strict_types=1);

namespace Shortlist;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed.
 */
final class Migrator
{
    private const OPTION   = 'shortlist_db_version';
    private const SETTINGS = 'shortlist_settings';

    public function maybeMigrate(): void
    {
        $current = (string) get_option(self::OPTION, '0');

        if (version_compare($current, VERSION, '>=')) {
            return;
        }

        $this->createWishlistTable();
        $this->seedDefaultSettings();

        // The wishlist account tab/shortcode uses a rewrite endpoint registered
        // on `init`; flush once so the new endpoint resolves without a manual
        // permalink save.
        flush_rewrite_rules(false);

        update_option(self::OPTION, VERSION, false);
    }

    /**
     * Create the wishlist items table. Owner is either a user id or a guest
     * session id; a product appears at most once per owner.
     */
    private function createWishlistTable(): void
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table   = $wpdb->prefix . 'shortlist_items';
        $collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            product_id bigint(20) unsigned NOT NULL,
            user_id bigint(20) unsigned DEFAULT NULL,
            session_id varchar(64) DEFAULT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY product_id (product_id)
        ) {$collate};";

        dbDelta($sql);
    }

    /**
     * Seed the default settings once, without clobbering an existing config.
     */
    private function seedDefaultSettings(): void
    {
        if (get_option(self::SETTINGS, null) !== null) {
            return;
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require SHORTLIST_DIR . 'config/defaults.php';

        add_option(self::SETTINGS, $defaults, '', false);
    }
}
