<?php
/**
 * Elementor widget: Wishlist.
 *
 * A thin wrapper around the [shortlist] shortcode so the wishlist can be placed
 * with the Elementor editor. Mirrors the bundled Gutenberg block (blocks/wishlist)
 * and renders the exact same body via the shortcode. Kept deliberately minimal
 * (renders the shortcode) so a future migration to Elementor v4 atomic widgets is
 * localized to this class. Loaded only from the `elementor/widgets/register` hook,
 * so the `\Elementor\Widget_Base` base class is guaranteed to exist here, works
 * on Elementor 3.x and 4.0.
 *
 * @package Shortlist
 */

declare(strict_types=1);

namespace Shortlist\Elementor;

defined('ABSPATH') || exit;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

/**
 * Wishlist Elementor widget.
 */
final class WishlistWidget extends Widget_Base
{
    /**
     * Widget machine name (matches the shortcode tag).
     */
    public function get_name(): string
    {
        return 'shortlist';
    }

    /**
     * Widget label shown in the editor.
     */
    public function get_title(): string
    {
        return esc_html__('Wishlist', 'plogins-shortlist');
    }

    /**
     * Editor panel icon.
     */
    public function get_icon(): string
    {
        return 'eicon-heart';
    }

    /**
     * Editor panel categories.
     *
     * @return string[]
     */
    public function get_categories(): array
    {
        return ['woocommerce-elements', 'general'];
    }

    /**
     * Search keywords in the editor.
     *
     * @return string[]
     */
    public function get_keywords(): array
    {
        return ['wishlist', 'shortlist', 'favourites', 'favorites', 'woocommerce'];
    }

    /**
     * Register the editor controls.
     */
    protected function register_controls(): void
    {
        $this->start_controls_section(
            'content',
            ['label' => esc_html__('Wishlist', 'plogins-shortlist')],
        );

        $this->add_control(
            'shortlist_note',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => esc_html__('Renders the current shopper\'s wishlist, the same output as the [shortlist] shortcode.', 'plogins-shortlist'),
                'content_classes' => 'elementor-descriptor',
            ],
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget on the front end and in the editor preview.
     */
    protected function render(): void
    {
        echo do_shortcode('[shortlist]');
    }
}
