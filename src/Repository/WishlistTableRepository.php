<?php

declare(strict_types=1);

namespace Shortlist\Repository;

use WPPoland\StorefrontKit\Wishlist\WishlistRepository;

defined('ABSPATH') || exit;

/**
 * Custom-table storage for the storefront-kit {@see WishlistRepository}.
 *
 * Items are addressed by product id plus exactly one owner: a logged-in
 * `$userId` or a guest `$sessionId`. The table (`{$prefix}shortlist_items`) is
 * created by the {@see \Shortlist\Migrator}. Newest items are returned first.
 */
final class WishlistTableRepository implements WishlistRepository
{
    public function table(): string
    {
        global $wpdb;

        return $wpdb->prefix . 'shortlist_items';
    }

    public function add(int $productId, ?int $userId, ?string $sessionId): void
    {
        global $wpdb;

        if ($this->exists($productId, $userId, $sessionId)) {
            return;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table.
        $wpdb->insert(
            $this->table(),
            [
                'product_id' => $productId,
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'created_at' => current_time('mysql', true),
            ],
            ['%d', '%d', '%s', '%s'],
        );
    }

    public function remove(int $productId, ?int $userId, ?string $sessionId): void
    {
        global $wpdb;

        $where  = ['product_id' => $productId];
        $format = ['%d'];

        if ($userId !== null) {
            $where['user_id'] = $userId;
            $format[]         = '%d';
        } elseif ($sessionId !== null) {
            $where['session_id'] = $sessionId;
            $format[]            = '%s';
        } else {
            return;
        }

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table.
        $wpdb->delete($this->table(), $where, $format);
    }

    public function exists(int $productId, ?int $userId, ?string $sessionId): bool
    {
        global $wpdb;

        if ($userId !== null) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table, prepared below.
            return (int) $wpdb->get_var(
                $wpdb->prepare(
                    'SELECT COUNT(*) FROM %i WHERE product_id = %d AND user_id = %d',
                    $this->table(),
                    $productId,
                    $userId,
                ),
            ) > 0;
        }

        if ($sessionId !== null) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table, prepared below.
            return (int) $wpdb->get_var(
                $wpdb->prepare(
                    'SELECT COUNT(*) FROM %i WHERE product_id = %d AND session_id = %s',
                    $this->table(),
                    $productId,
                    $sessionId,
                ),
            ) > 0;
        }

        return false;
    }

    /**
     * @return list<int>
     */
    public function findProductIds(?int $userId, ?string $sessionId): array
    {
        global $wpdb;

        if ($userId !== null) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table, prepared below.
            $ids = $wpdb->get_col(
                $wpdb->prepare(
                    'SELECT product_id FROM %i WHERE user_id = %d ORDER BY created_at DESC',
                    $this->table(),
                    $userId,
                ),
            );
        } elseif ($sessionId !== null) {
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table, prepared below.
            $ids = $wpdb->get_col(
                $wpdb->prepare(
                    'SELECT product_id FROM %i WHERE session_id = %s ORDER BY created_at DESC',
                    $this->table(),
                    $sessionId,
                ),
            );
        } else {
            return [];
        }

        return array_values(array_map('intval', is_array($ids) ? $ids : []));
    }

    public function transferSessionToUser(string $sessionId, int $userId): void
    {
        global $wpdb;

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching -- Custom plugin table, prepared below.
        $wpdb->query(
            $wpdb->prepare(
                'UPDATE %i SET user_id = %d WHERE session_id = %s AND (user_id IS NULL OR user_id = 0)',
                $this->table(),
                $userId,
                $sessionId,
            ),
        );
    }
}
