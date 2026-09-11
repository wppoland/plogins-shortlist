<?php

/**
 * The wishlist stylesheet and script must load on every page that prints the
 * wishlist, and on no other page.
 *
 * shouldEnqueueAssets() listed the pages the PLUGIN puts the wishlist on (shop,
 * product, product taxonomy, my account, and the configured wishlist page) and
 * none of the pages a MERCHANT puts it on. The `[shortlist]` shortcode and the
 * shortlist/wishlist block render the full markup anywhere, so on any other
 * page the wishlist arrived with no stylesheet and no script: an unstyled list
 * whose remove buttons did nothing, with no error to explain it.
 *
 * The other half matters just as much. Loading the assets everywhere to be safe
 * would put two files on every page of the site, so the "unrelated page" cases
 * below are not padding.
 *
 * Run: php tests/wishlist-assets-scope-check.php
 */

declare(strict_types=1);

// Page context the stubs below answer from. One array, so each case is a
// single readable assignment.
$ctx = [
    'admin' => false, 'shop' => false, 'product' => false, 'tax' => false,
    'account' => false, 'singular' => false, 'page_id' => 0,
    'content' => '', 'blocks' => [],
];

$enqueued = [];

function is_admin(): bool { global $ctx; return $ctx['admin']; }
function is_shop(): bool { global $ctx; return $ctx['shop']; }
function is_product(): bool { global $ctx; return $ctx['product']; }
function is_product_taxonomy(): bool { global $ctx; return $ctx['tax']; }
function is_account_page(): bool { global $ctx; return $ctx['account']; }
function is_singular(): bool { global $ctx; return $ctx['singular']; }
function is_page($id = null): bool { global $ctx; return $ctx['page_id'] > 0 && (int) $id === $ctx['page_id']; }
function get_post() { global $ctx; return $ctx['singular'] ? new WP_Post($ctx['content']) : null; }
function has_shortcode(string $content, string $tag): bool { return str_contains($content, '[' . $tag); }
function has_block(string $name, $post = null): bool { global $ctx; return in_array($name, $ctx['blocks'], true); }
function wp_enqueue_style(string $h, ...$a): void { global $enqueued; $enqueued[] = 'style:' . $h; }
function wp_enqueue_script(string $h, ...$a): void { global $enqueued; $enqueued[] = 'script:' . $h; }
function wp_localize_script(...$a): bool { return true; }
function wp_create_nonce($a = ''): string { return 'nonce'; }
function admin_url(string $p = ''): string { return 'https://example.test/wp-admin/' . $p; }
function wc_get_page_permalink(string $p): string { return 'https://example.test/' . $p; }
function is_user_logged_in(): bool { return false; }
function __(string $t, string $d = ''): string { return $t; }

class WP_Post
{
    public function __construct(public string $post_content)
    {
    }
}

require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistRepository.php';
require __DIR__ . '/../lib/storefront-kit/Wishlist/WishlistEngine.php';

final class NullRepo implements \WPPoland\StorefrontKit\Wishlist\WishlistRepository
{
    public function add(int $p, ?int $u, ?string $s): void {}
    public function remove(int $p, ?int $u, ?string $s): void {}
    public function exists(int $p, ?int $u, ?string $s): bool { return false; }
    public function findProductIds(?int $u, ?string $s): array { return []; }
    public function transferSessionToUser(string $s, int $u): void {}
}

$engine = new \WPPoland\StorefrontKit\Wishlist\WishlistEngine(
    repository: new NullRepo(),
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

$failures = [];

$case = static function (string $label, array $context, bool $want) use ($engine, &$ctx, &$enqueued, &$failures): void {
    $ctx = array_merge(
        ['admin' => false, 'shop' => false, 'product' => false, 'tax' => false,
         'account' => false, 'singular' => false, 'page_id' => 0, 'content' => '', 'blocks' => []],
        $context,
    );
    $enqueued = [];
    $engine->enqueueAssets();
    $got = in_array('style:shortlist', $enqueued, true) && in_array('script:shortlist', $enqueued, true);

    echo ($got === $want ? '  ok      ' : '  FAILED  ')
        . str_pad($label, 46) . ($want ? 'expect loaded' : 'expect absent')
        . ($got === $want ? '' : sprintf('  (got %s)', $got ? 'loaded' : 'absent')) . "\n";

    if ($got !== $want) {
        $failures[] = $label;
    }
};

// The pages the plugin itself puts the wishlist on.
$case('shop archive', ['shop' => true], true);
$case('single product', ['product' => true], true);
$case('product category', ['tax' => true], true);
$case('my account', ['account' => true], true);
$case('the configured wishlist page', ['singular' => true, 'page_id' => 42], true);

// The pages a merchant puts it on. These are the regression.
$case('any page with the [shortlist] shortcode', ['singular' => true, 'page_id' => 7, 'content' => 'Hi [shortlist] bye'], true);
$case('any page with the wishlist block', ['singular' => true, 'page_id' => 7, 'blocks' => ['shortlist/wishlist']], true);

// And the other half: not everywhere.
$case('an unrelated page', ['singular' => true, 'page_id' => 7, 'content' => 'Hello.'], false);
$case('an unrelated post with another block', ['singular' => true, 'page_id' => 7, 'blocks' => ['core/paragraph']], false);
$case('a non-singular archive', [], false);
$case('wp-admin', ['admin' => true, 'product' => true], false);

echo "\n" . ($failures === [] ? "RESULT: pass\n" : 'RESULT: ' . count($failures) . " failed\n");
exit($failures === [] ? 0 : 1);
