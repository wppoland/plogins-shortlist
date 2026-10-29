<?php

declare(strict_types=1);

/**
 * Tests for Shortlist ecosystem integrations (Quotlet and Waitlist).
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
        ) {
        }

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

// Test 1: Quotlet integration without Estimate\Plugin defined -> should output nothing
ob_start();
$service->renderQuoteIntegration([new WC_Product(10)], ['enable_quote_integration' => true]);
$output = ob_get_clean();
check('' === $output, 'Quote integration renders nothing when Quotlet is absent');

// Test 2: Quotlet integration with Estimate\Plugin defined
if (! class_exists('Estimate\Plugin')) {
    eval('namespace Estimate; class Plugin {}');
}

ob_start();
$service->renderQuoteIntegration([new WC_Product(10)], ['enable_quote_integration' => true]);
$output = ob_get_clean();
check(str_contains($output, 'shortlist-quote-button'), 'Quote integration renders button when Quotlet is present');

// Test 3: Quotlet integration disabled via settings
ob_start();
$service->renderQuoteIntegration([new WC_Product(10)], ['enable_quote_integration' => false]);
$output = ob_get_clean();
check('' === $output, 'Quote integration renders nothing when disabled in settings');

// Test 4: Waitlist integration on in-stock item -> should output nothing
$inStockProduct = new WC_Product(11, true);
ob_start();
$service->renderWaitlistIntegration($inStockProduct, ['enable_waitlist_integration' => true]);
$output = ob_get_clean();
check('' === $output, 'Waitlist integration renders nothing for in-stock product');

// Test 5: Waitlist integration on out-of-stock item without Waitlist\Plugin -> should output nothing
$outOfStockProduct = new WC_Product(12, false, 'https://example.com/product/out-of-stock');
ob_start();
$service->renderWaitlistIntegration($outOfStockProduct, ['enable_waitlist_integration' => true]);
$output = ob_get_clean();
check('' === $output, 'Waitlist integration renders nothing when Waitlist plugin is absent');

// Test 6: Waitlist integration on out-of-stock item with Waitlist\Plugin
if (! class_exists('Waitlist\Plugin')) {
    eval('namespace Waitlist; class Plugin {}');
}

ob_start();
$service->renderWaitlistIntegration($outOfStockProduct, ['enable_waitlist_integration' => true]);
$output = ob_get_clean();
check(str_contains($output, 'shortlist-waitlist-button') && str_contains($output, '#waitlist'), 'Waitlist integration renders notify button on out-of-stock product');

// Test 7: Waitlist integration disabled via settings
ob_start();
$service->renderWaitlistIntegration($outOfStockProduct, ['enable_waitlist_integration' => false]);
$output = ob_get_clean();
check('' === $output, 'Waitlist integration renders nothing when disabled in settings');

if ($fail > 0) {
    echo "\n{$fail} checks failed!\n";
    exit(1);
}

echo "\nAll Shortlist ecosystem integration checks passed.\n";
