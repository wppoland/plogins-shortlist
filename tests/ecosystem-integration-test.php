<?php

declare(strict_types=1);

/**
 * Shortlist's Waitlist integration: the Waitlist form inline on out-of-stock
 * items, a product-page link for variable products. The wishlist-to-quote
 * button is Quotlet's own and is checked in Quotlet.
 *
 *   php tests/ecosystem-integration-test.php
 */

define('ABSPATH', __DIR__);
define('SHORTLIST_DIR', dirname(__DIR__) . '/');

if (! function_exists('__')) {
    function __($text, $domain = null) { return $text; }
}
if (! function_exists('esc_html')) {
    function esc_html($text) { return htmlspecialchars((string) $text, ENT_QUOTES); }
}
if (! function_exists('esc_html_e')) {
    function esc_html_e($text, $domain = null) { echo esc_html($text); }
}
if (! function_exists('esc_attr')) {
    function esc_attr($text) { return htmlspecialchars((string) $text, ENT_QUOTES); }
}
if (! function_exists('esc_attr_e')) {
    function esc_attr_e($text, $domain = null) { echo esc_attr($text); }
}
if (! function_exists('esc_url')) {
    function esc_url($url) { return $url; }
}
if (! function_exists('wp_nonce_field')) {
    function wp_nonce_field($action, $name = '_wpnonce') {
        echo '<input type="hidden" name="' . esc_attr($name) . '" value="test_nonce" />';
    }
}
$shortcodes = [];
function shortcode_exists($tag) { return isset($GLOBALS['shortcodes'][$tag]); }
function do_shortcode($content) {
    return preg_match('/^\[restock_waitlist id="(\d+)"\]$/', $content, $m) && isset($GLOBALS['shortcodes']['restock_waitlist'])
        ? ($GLOBALS['shortcodes']['restock_waitlist'])((int) $m[1])
        : $content;
}
if (! function_exists('apply_filters')) {
    function apply_filters($hook, $value, ...$args) { return $value; }
}

$fail = 0;
function check(bool $ok, string $what): void
{
    global $fail;
    echo ($ok ? 'ok   ' : 'FAIL ') . $what . "\n";
    if (! $ok) {
        $fail++;
    }
}

// Mock WC_Product
if (! class_exists('WC_Product')) {
    class WC_Product
    {
        public function __construct(
            private int $id = 1,
            private bool $inStock = true,
            private string $permalink = 'https://example.com/product/test',
            private string $type = 'simple',
        ) {
        }

        public function is_type(string $type): bool { return $this->type === $type; }
        public function get_id(): int { return $this->id; }
        public function is_in_stock(): bool { return $this->inStock; }
        public function get_permalink(): string { return $this->permalink; }
    }
}

spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'Shortlist\\')) {
        $file = dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($class, 10)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use Shortlist\Service\ShortlistService;

$service = new ShortlistService();

$render = static function (WC_Product $product, bool $enabled = true) use ($service): string {
    ob_start();
    $service->renderWaitlistIntegration($product, ['enable_waitlist_integration' => $enabled]);
    return (string) ob_get_clean();
};
$simpleOut   = new WC_Product(12, false, 'https://example.com/product/out-of-stock');
$variableOut = new WC_Product(13, false, 'https://example.com/product/variable', 'variable');

check('' === $render($simpleOut), 'nothing without the Waitlist shortcode');
check('' === $render($variableOut), 'no variable-product link without Waitlist either');

// Waitlist present: its shortcode answers for out-of-stock products only, as the real one does.
$shortcodes['restock_waitlist'] = static fn (int $id): string => 12 === $id ? '<div class="restock-waitlist" data-id="12"></div>' : '';

check(str_contains($render($simpleOut), 'class="restock-waitlist" data-id="12"'), 'out-of-stock simple product gets the Waitlist form inline');
check(! str_contains($render($simpleOut), '#waitlist'), 'no dead #waitlist anchor');
check('' === $render(new WC_Product(11, true)), 'in-stock product gets nothing');
$link = $render($variableOut);
check(str_contains($link, 'href="https://example.com/product/variable"') && str_contains($link, 'shortlist-waitlist-button'), 'out-of-stock variable product links to its page');
check('' === $render(new WC_Product(14, true, 'https://example.com/product/v', 'variable')), 'variable product with stock gets nothing');
check('' === $render($simpleOut, false), 'nothing when switched off in settings');
check(! method_exists($service, 'renderQuoteIntegration') && ! method_exists($service, 'maybeHandleQuoteRequest'), 'quote button and handler left Shortlist');

if ($fail > 0) {
    echo "\n{$fail} checks failed!\n";
    exit(1);
}

echo "\nAll Shortlist Waitlist integration checks passed.\n";
