<?php
/**
 * Constants needed by PHPStan to analyse the plugin without bootstrapping WordPress.
 *
 * @package Shortlist
 */

declare(strict_types=1);

namespace {
    if (! defined('ABSPATH')) {
        define('ABSPATH', '/tmp/wordpress/');
    }
    if (! defined('SHORTLIST_DIR')) {
        define('SHORTLIST_DIR', '/tmp/shortlist/');
    }
    if (! defined('SHORTLIST_URL')) {
        define('SHORTLIST_URL', 'https://example.test/wp-content/plugins/shortlist/');
    }
}

namespace Shortlist {
    if (! defined('Shortlist\\VERSION')) {
        define('Shortlist\\VERSION', '0.1.0');
    }
    if (! defined('Shortlist\\PLUGIN_FILE')) {
        define('Shortlist\\PLUGIN_FILE', '/tmp/shortlist/shortlist.php');
    }
}
