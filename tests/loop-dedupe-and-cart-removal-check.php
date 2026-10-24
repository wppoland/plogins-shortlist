<?php

/**
 * Two engine paths added in 1.1.0.
 *
 * 1. Product Collection cards get the loop button from a render_block filter,
 *    and on a block theme's shop page WooCommerce also runs the classic
 *    woocommerce_after_shop_loop_item hook inside the same grid. Each product
 *    must get one button, never two, and a variable product none.
 * 2. "Remove when added to cart" drops one product from the current owner's
 *    list. A guest without a session has nothing to remove and must not have
 *    one minted for them.
 *
 * Run: php tests/loop-dedupe-and-cart-removal-check.php
 */

declare(strict_types=1);

$wishlist_test_user = 0;

// phpcs:disable
function get_current_user_id(): int { global $wishlist_test_user; return $wishlist_test_user; }
function is_user_logged_in(): bool { return get_current_user_id() > 0; }
function sanitize_text_field($text) { return (string) $text; }
function wp_unslash($value) { return $value; }

class WC_Product
{
    public function __construct(private int $id, private string $type = 'simple') {}
    public function get_id(): int { return $this->id; }
    public function is_type($type): bool { return $type === $this->type; }
}
// phpcs:enable

require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistRepository.php';
require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistEngine.php';

final class RecordingRepo implements \WPPoland\StorefrontKit\Wishlist\WishlistRepository
{
    /** @var list<array{int, ?int, ?string}> */
    public array $removed = [];

    public function add(int $p, ?int $u, ?string $s): void {}
    public function remove(int $p, ?int $u, ?string $s): void { $this->removed[] = [$p, $u, $s]; }
    public function exists(int $p, ?int $u, ?string $s): bool { return false; }
    public function findProductIds(?int $u, ?string $s): array { return []; }
    public function countProductIds(?int $u, ?string $s): int { return 0; }
    public function transferSessionToUser(string $s, int $u): void {}
}

function wishlist_test_engine(RecordingRepo $repo): \WPPoland\StorefrontKit\Wishlist\WishlistEngine
{
    return new \WPPoland\StorefrontKit\Wishlist\WishlistEngine(
        repository: $repo,
        ajaxAction: 'a',
        nonceAction: 'n',
        scriptObjectName: 'o',
        assetHandle: 'h',
        styleUrl: '',
        scriptUrl: '',
        version: '1',
        endpoint: 'shortlist',
        guestCookie: 'shortlist_session',
        loopButtonTemplate: 'loop',
        singleButtonTemplate: 'single',
        accountTemplate: 'account',
        labels: ['add' => 'Add', 'remove' => 'Remove'],
        isEnabled: static fn (): bool => true,
        settings: static fn (): array => ['show_on_loop' => true, 'allow_guests' => true],
        renderTemplate: static function (string $t, array $c): void { echo '<button data-id="' . $c['button']['product_id'] . '"></button>'; },
        renderAccount: static fn (string $t, array $c): string => '',
    );
}

$failures = [];

// 1. One button per product per request, none for a variable product.
$engine = wishlist_test_engine(new RecordingRepo());
$first  = $engine->renderLoopButtonHtml(new WC_Product(10));
$second = $engine->renderLoopButtonHtml(new WC_Product(10));
$other  = $engine->renderLoopButtonHtml(new WC_Product(11));
$var    = $engine->renderLoopButtonHtml(new WC_Product(12, 'variable'));
if ($first === '' || $other === '') {
    $failures[] = 'a simple product got no loop button';
}
if ($second !== '') {
    $failures[] = 'the same product got a second loop button (block filter + classic hook)';
}
if ($var !== '') {
    $failures[] = 'a variable product got a loop button';
}

// 2a. Guest without a session: nothing removed, no session minted.
$repo = new RecordingRepo();
unset($_COOKIE['shortlist_session']);
wishlist_test_engine($repo)->removeProduct(10);
if ($repo->removed !== [] || isset($_COOKIE['shortlist_session'])) {
    $failures[] = 'a guest without a session triggered a removal or got a session';
}

// 2b. Guest with a session: removed from that session only.
$_COOKIE['shortlist_session'] = 'abc';
wishlist_test_engine($repo)->removeProduct(15);
if ($repo->removed !== [[15, null, 'abc']]) {
    $failures[] = 'guest removal went to ' . json_encode($repo->removed);
}

// 2c. Signed-in customer: removed from the account, not the stale cookie.
$repo = new RecordingRepo();
$wishlist_test_user = 7;
wishlist_test_engine($repo)->removeProduct(15);
if ($repo->removed !== [[15, 7, null]]) {
    $failures[] = 'customer removal went to ' . json_encode($repo->removed);
}

if ([] !== $failures) {
    fwrite(STDERR, "loop-dedupe-and-cart-removal-check: FAIL\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '  ' . $failure . "\n");
    }
    exit(1);
}

echo "loop-dedupe-and-cart-removal-check: OK (dedupe, variable skip, 3 owner cases)\n";
