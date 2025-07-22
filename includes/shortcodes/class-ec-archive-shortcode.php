<?php

/**
 * EC Archive Shortcode
 *
 * Handles the [ec_event_archive] shortcode.
 *
 * @package Satori_EC
 */

defined('ABSPATH') || exit; // Exit if accessed directly

class EC_Archive_Shortcode
{

    // ------------------------------------------
    // Register shortcode
    // ------------------------------------------
    public static function register()
    {
        add_shortcode('ec_event_archive', [__CLASS__, 'render_shortcode']);
    }

    // ------------------------------------------
    // Render the event archive via shortcode
    // ------------------------------------------
    public static function render_shortcode($atts)
    {
        // --------------------------------------
        // Define and sanitize shortcode attributes
        // --------------------------------------
        $atts = shortcode_atts([
            'category'  => '',
            'view'      => 'grid',
            'per_page'  => 9,
        ], $atts, 'ec_event_archive');

        $sc_category  = sanitize_text_field($atts['category']);
        $sc_view      = sanitize_text_field($atts['view']);
        $sc_per_page  = absint($atts['per_page']);

        // --------------------------------------
        // Handle URL-based filtering and pagination
        // --------------------------------------
        $paged        = max(1, get_query_var('paged', 1));
        $search       = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $url_category = isset($_GET['ec_category']) ? sanitize_text_field($_GET['ec_category']) : '';
        $url_view     = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : '';

        // Final values, giving priority to URL overrides
        $final_category = $url_category ? $url_category : $sc_category;
        $final_view     = $url_view ? $url_view : $sc_view;

        // --------------------------------------
        // Build query arguments
        // --------------------------------------
        $args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'paged'          => $paged,
            's'              => $search,
            'posts_per_page' => $sc_per_page,
        ];

        if ($final_category) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $final_category,
                ],
            ];
        }

        $query = new WP_Query($args);

        // --------------------------------------
        // Load output buffer and template
        // --------------------------------------
        ob_start();

        $template_path = ec_locate_template('archive-event-shortcode.php');

        if ($template_path) {
            // Variables available to the template
            $final_view = $final_view;
            include $template_path;
        } else {
            echo '<p>' . esc_html__('Event archive template not found.', 'satori-ec') . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
