<?php
/**
 * Uninstall cleanup for Shortlist.
 *
 * Runs only when the plugin is deleted from wp-admin. Removes the custom
 * wishlist items table and every option the plugin stores, so deleting the
 * plugin leaves no data behind.
 *
 * @package Shortlist
 */

declare(strict_types=1);

defined('WP_UNINSTALL_PLUGIN') || exit;

global $wpdb;

// Drop the custom wishlist items table created by the Migrator.
$shortlist_table = $wpdb->prefix . 'shortlist_items';
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange -- Custom plugin table removed on uninstall.
$wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS %i', $shortlist_table));

// Remove plugin options.
delete_option('shortlist_settings');
delete_option('shortlist_db_version');

// The PRO banner's dismissal is stored per user, so it belongs to the
// plugin rather than to the site content. User meta is global, not
// per-site, which is why this uses delete_metadata's \$delete_all rather
// than a loop over the users of one blog.
delete_metadata('user', 0, 'shortlist_pro_banner_dismissed', '', true);
