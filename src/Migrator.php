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
        $changed = false;

        if (version_compare($current, '0.1.0', '<')) {
            $this->createWishlistTable();
            $this->seedDefaultSettings();
            $changed = true;
        }

        if (version_compare($current, '0.3.1', '<')) {
            $this->addListIdColumn();
            $changed = true;
        }

        if (version_compare($current, VERSION, '<')) {
            if ($changed) {
                flush_rewrite_rules(false);
            }

            update_option(self::OPTION, VERSION, false);
        }
    }

    /**
     * Create the wishlist items table. Owner is either a user id or a guest
     * session id; a product appears at most once per owner per list.
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
            list_id bigint(20) unsigned NOT NULL DEFAULT 0,
            user_id bigint(20) unsigned DEFAULT NULL,
            session_id varchar(64) DEFAULT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY product_id (product_id),
            KEY list_id (list_id)
        ) {$collate};";

        dbDelta($sql);
    }

    /**
     * Forward migration for stores that created the table before list_id existed.
     */
    private function addListIdColumn(): void
    {
        global $wpdb;

        $table = $wpdb->prefix . 'shortlist_items';

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Schema migration on own plugin table.
        $column = $wpdb->get_results(
            $wpdb->prepare('SHOW COLUMNS FROM %i LIKE %s', $table, 'list_id'),
        );
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter

        if (is_array($column) && $column !== []) {
            return;
        }

        // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter -- Schema migration on own plugin table.
        $wpdb->query("ALTER TABLE {$table} ADD COLUMN list_id bigint(20) unsigned NOT NULL DEFAULT 0 AFTER product_id");
        $wpdb->query("ALTER TABLE {$table} ADD KEY list_id (list_id)");
        // phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,PluginCheck.Security.DirectDB.UnescapedDBParameter
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
