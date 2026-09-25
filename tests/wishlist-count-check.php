<?php

/**
 * The saved-item count must be a count, not a list that gets counted.
 *
 * getCount() returned count($this->getProducts()), so the number behind the My
 * Account menu label, and the number sent back to the page after every add or
 * remove, read the whole item list out of the database and then built one
 * wc_get_product() per row to throw the objects away again. A shopper with
 * forty saved items paid forty product loads for one integer, on every My
 * Account page view.
 *
 * This harness runs the real engine against a repository stub and asserts the
 * number is right, that no product is loaded to produce it, and that the
 * deleted-product case still agrees with the list the account page renders,
 * which is the reason the count query joins the posts table at all.
 *
 * Run: php tests/wishlist-count-check.php
 */

declare(strict_types=1);

/** @var int $wishlist_test_hydrations wc_get_product() calls. */
$wishlist_test_hydrations = 0;

/** @var int $wishlist_test_list_reads findProductIds() calls. */
$wishlist_test_list_reads = 0;

// phpcs:disable
function get_current_user_id(): int { return 7; }
function sanitize_text_field($text) { return (string) $text; }
function wp_unslash($value) { return $value; }
function __(string $text, string $domain = ''): string { return $text; }

function wc_get_product($id = null)
{
    global $wishlist_test_hydrations;
    ++$wishlist_test_hydrations;

    // Product 33 was deleted after it was saved, which is how a stored row
    // stops being a card on the account page.
    return 33 === (int) $id ? false : new WC_Product((int) $id);
}

class WC_Product
{
    public function __construct(private int $id) {}
    public function get_id(): int { return $this->id; }
    public function get_parent_id(): int { return 0; }
}
function get_post_status($id) { return 34 === (int) $id ? 'draft' : 'publish'; }
function current_user_can($cap, ...$args) { return false; }
// phpcs:enable

require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistRepository.php';
require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistEngine.php';

/**
 * Stands in for WishlistTableRepository. findProductIds() returns the stored
 * rows; countProductIds() answers the way the real COUNT(*) does, joined to the
 * posts table, so a row whose product is gone is not counted.
 */
final class CountingRepo implements \WPPoland\StorefrontKit\Wishlist\WishlistRepository
{
    /** @param list<int> $rows */
    public function __construct(private array $rows)
    {
    }

    public function add(int $p, ?int $u, ?string $s): void {}
    public function remove(int $p, ?int $u, ?string $s): void {}
    public function exists(int $p, ?int $u, ?string $s): bool { return in_array($p, $this->rows, true); }

    /** @return list<int> */
    public function findProductIds(?int $u, ?string $s): array
    {
        global $wishlist_test_list_reads;
        ++$wishlist_test_list_reads;

        return $this->rows;
    }

    public function countProductIds(?int $u, ?string $s): int
    {
        return count(array_filter($this->rows, static fn (int $id): bool => 33 !== $id));
    }

    public function transferSessionToUser(string $s, int $u): void {}
}

/** Build the engine over a given set of stored rows. */
function wishlist_test_engine(array $rows): \WPPoland\StorefrontKit\Wishlist\WishlistEngine
{
    return new \WPPoland\StorefrontKit\Wishlist\WishlistEngine(
        repository: new CountingRepo($rows),
        ajaxAction: 'shortlist_wishlist_toggle',
        nonceAction: 'shortlist_wishlist',
        scriptObjectName: 'shortlistWishlist',
        assetHandle: 'shortlist',
        styleUrl: 'https://example.test/wishlist.css',
        scriptUrl: 'https://example.test/wishlist.js',
        version: '1.0.0',
        endpoint: 'shortlist',
        guestCookie: 'shortlist_session',
        loopButtonTemplate: 'loop',
        singleButtonTemplate: 'single',
        accountTemplate: 'account',
        labels: [],
        isEnabled: static fn (): bool => true,
        settings: static fn (): array => ['wishlist_page_id' => 42],
        renderTemplate: static function (string $t, array $c): void {},
        renderAccount: static fn (string $t, array $c): string => '',
        shortcodeTag: 'shortlist',
        blockName: 'shortlist/wishlist',
    );
}

/**
 * @param list<int> $rows
 * @return array{0: int, 1: int, 2: int} count, hydrations, list reads
 */
function wishlist_test_count(array $rows): array
{
    global $wishlist_test_hydrations, $wishlist_test_list_reads;
    $wishlist_test_hydrations = 0;
    $wishlist_test_list_reads = 0;

    $count = wishlist_test_engine($rows)->getCount();

    return [$count, $wishlist_test_hydrations, $wishlist_test_list_reads];
}

$failures = [];

// Forty saved items: one number, no product loaded, no list read.
$forty = range(100, 139);
[$count, $hydrations, $listReads] = wishlist_test_count($forty);
if (40 !== $count) {
    $failures[] = sprintf('forty saved items counted as %d', $count);
}
if (0 !== $hydrations) {
    $failures[] = sprintf('counting forty items loaded %d product object(s), expected 0', $hydrations);
}
if (0 !== $listReads) {
    $failures[] = sprintf('counting forty items read the item list %d time(s), expected 0', $listReads);
}

// An empty wishlist.
[$count, $hydrations] = wishlist_test_count([]);
if (0 !== $count || 0 !== $hydrations) {
    $failures[] = sprintf('an empty wishlist counted %d with %d product load(s)', $count, $hydrations);
}

// A row whose product was deleted is not a card on the account page, so it must
// not be in the badge either. This is what the join to the posts table is for.
[$count, $hydrations] = wishlist_test_count([31, 32, 33]);
if (2 !== $count) {
    $failures[] = sprintf('a deleted product was counted: got %d, expected 2', $count);
}

// And the number still agrees with the list the account page actually renders.
$rendered = count(wishlist_test_engine([31, 32, 33])->getProducts());
if ($rendered !== 2) {
    $failures[] = sprintf('the account page rendered %d product(s), expected 2', $rendered);
}

// A draft product must not be shown back to someone who cannot read it,
// whatever id was posted to the toggle.
$rendered = count(wishlist_test_engine([31, 34])->getProducts());
if ($rendered !== 1) {
    $failures[] = sprintf('a draft product was rendered: got %d product(s), expected 1', $rendered);
}

if ([] !== $failures) {
    fwrite(STDERR, "wishlist-count-check: FAIL\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '  ' . $failure . "\n");
    }
    exit(1);
}

echo "wishlist-count-check: OK (5 cases, the count loads nothing, drafts are not shown)\n";
