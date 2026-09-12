<?php

declare(strict_types=1);

namespace WPPoland\StorefrontKit\Wishlist;

/**
 * Storage contract for the wishlist engine. The host plugin implements this
 * against its own table / option store. Items are addressed by product id plus
 * exactly one owner: a logged-in `$userId` or a guest `$sessionId`.
 */
interface WishlistRepository
{
    public function add(int $productId, ?int $userId, ?string $sessionId): void;

    public function remove(int $productId, ?int $userId, ?string $sessionId): void;

    public function exists(int $productId, ?int $userId, ?string $sessionId): bool;

    /**
     * Ordered list of stored product ids for the given owner.
     *
     * @return list<int>
     */
    public function findProductIds(?int $userId, ?string $sessionId): array;

    /**
     * How many items the given owner has stored.
     *
     * Separate from {@see findProductIds()} on purpose: a header badge and the
     * My Account menu want the number, and counting by hydrating one
     * `wc_get_product()` per saved row is a query per row for a figure the
     * database already knows.
     */
    public function countProductIds(?int $userId, ?string $sessionId): int;

    /**
     * Reassign a guest session's items to a user (called on login).
     */
    public function transferSessionToUser(string $sessionId, int $userId): void;
}
