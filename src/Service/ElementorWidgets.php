<?php
/**
 * Elementor integration service.
 *
 * Registers the Shortlist Elementor widget(s). The `elementor/widgets/register`
 * action only fires when Elementor is active, so this service is self-guarding:
 * nothing loads unless Elementor is present. Works on Elementor 3.x and 4.0.
 *
 * @package Shortlist
 */

declare(strict_types=1);

namespace Shortlist\Service;

defined('ABSPATH') || exit;

use Shortlist\Contract\HasHooks;
use Shortlist\Elementor\WishlistWidget;

/**
 * Wires the Shortlist widget into the Elementor editor.
 */
final class ElementorWidgets implements HasHooks
{
    public function registerHooks(): void
    {
        add_action('elementor/widgets/register', [$this, 'register']);
    }

    /**
     * Register widget instances with Elementor's widgets manager.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register($widgets_manager): void
    {
        // Loaded here (not autoloaded) so \Elementor\Widget_Base always exists.
        require_once SHORTLIST_DIR . 'src/Elementor/WishlistWidget.php';
        $widgets_manager->register(new WishlistWidget());
    }
}
