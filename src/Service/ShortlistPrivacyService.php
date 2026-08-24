<?php

declare(strict_types=1);

namespace Shortlist\Service;

use Shortlist\Contract\HasHooks;
use Shortlist\Repository\WishlistTableRepository;
use WP_User;

defined('ABSPATH') || exit;

/**
 * Personal data exporter and eraser for Shortlist wishlist items.
 */
final class ShortlistPrivacyService implements HasHooks
{
    private const PAGE_SIZE = 100;

    public function __construct(
        private readonly WishlistTableRepository $repository,
    ) {
    }

    public function registerHooks(): void
    {
        add_filter('wp_privacy_personal_data_exporters', [$this, 'registerExporters']);
        add_filter('wp_privacy_personal_data_erasers', [$this, 'registerErasers']);
    }

    /**
     * @param array<string, array<string, mixed>> $exporters
     * @return array<string, array<string, mixed>>
     */
    public function registerExporters(array $exporters): array
    {
        $exporters['shortlist-items'] = [
            'exporter_friendly_name' => __('Shortlist Wishlist Items', 'plogins-shortlist'),
            'callback'               => [$this, 'exportWishlist'],
        ];

        return $exporters;
    }

    /**
     * @param array<string, array<string, mixed>> $erasers
     * @return array<string, array<string, mixed>>
     */
    public function registerErasers(array $erasers): array
    {
        $erasers['shortlist-items'] = [
            'eraser_friendly_name' => __('Shortlist Wishlist Items', 'plogins-shortlist'),
            'callback'             => [$this, 'eraseWishlist'],
        ];

        return $erasers;
    }

    /**
     * @return array{data: list<array<string, mixed>>, done: bool}
     */
    public function exportWishlist(string $email, int $page = 1): array
    {
        $user = get_user_by('email', $email);
        if (! $user instanceof WP_User) {
            return ['data' => [], 'done' => true];
        }

        $page   = max(1, $page);
        $offset = ($page - 1) * self::PAGE_SIZE;

        $items = [];
        $rows  = $this->repository->findItemsByUser((int) $user->ID, self::PAGE_SIZE, $offset);

        foreach ($rows as $r) {
            $product     = function_exists('wc_get_product') ? wc_get_product($r['product_id']) : null;
            $productName = $product ? $product->get_name() : sprintf(__('Product #%d', 'plogins-shortlist'), $r['product_id']);

            $items[] = [
                'group_id'    => 'shortlist-items',
                'group_label' => __('Shortlist Wishlist Items', 'plogins-shortlist'),
                'item_id'     => 'wishlist-item-' . $r['id'],
                'data'        => [
                    ['name' => __('Product ID', 'plogins-shortlist'), 'value' => (string) $r['product_id']],
                    ['name' => __('Product', 'plogins-shortlist'), 'value' => $productName],
                    ['name' => __('Added At', 'plogins-shortlist'), 'value' => $r['created_at']],
                ],
            ];
        }

        return [
            'data' => $items,
            'done' => count($rows) < self::PAGE_SIZE,
        ];
    }

    /**
     * @return array{items_removed: int, items_retained: int, messages: list<string>, done: bool}
     */
    public function eraseWishlist(string $email, int $page = 1): array
    {
        $user = get_user_by('email', $email);
        if (! $user instanceof WP_User) {
            return [
                'items_removed'  => 0,
                'items_retained' => 0,
                'messages'       => [],
                'done'           => true,
            ];
        }

        $removed = $this->repository->deleteByUser((int) $user->ID);

        return [
            'items_removed'  => $removed,
            'items_retained' => 0,
            'messages'       => [],
            'done'           => true,
        ];
    }
}
