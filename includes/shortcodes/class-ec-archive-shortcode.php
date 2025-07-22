<?php

/**
 * EC Archive Shortcode
 *
 * Handles the [ec_event_archive] shortcode for displaying events archive.
 *
 * @package Satori_EC
 */

defined('ABSPATH') || exit; // Exit if accessed directly

class EC_Archive_Shortcode
{

    // ------------------------------------------
    // Register shortcode [ec_event_archive]
    // ------------------------------------------
    public static function register()
    {
        add_shortcode('ec_event_archive', [__CLASS__, 'render_shortcode']);
    }

    // ------------------------------------------
    // Render the event archive via shortcode
    //
    // @param array $atts Shortcode attributes.
    // @return string HTML output of the event archive.
    // ------------------------------------------
    public static function render_shortcode($atts)
    {
        // --------------------------------------
        // Define and sanitize shortcode attributes with defaults
        // --------------------------------------
        $atts = shortcode_atts([
            'category' => '',
            'view'     => 'grid',
            'per_page' => 9,
        ], $atts, 'ec_event_archive');

        $sc_category = sanitize_text_field($atts['category']);
        $sc_view     = sanitize_text_field($atts['view']);
        $sc_per_page = absint($atts['per_page']);

        // --------------------------------------
        // Get URL parameters for filtering & pagination (overrides shortcode attributes)
        // --------------------------------------
        $paged        = max(1, get_query_var('paged', 1));
        $search       = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
        $url_category = isset($_GET['ec_category']) ? sanitize_text_field(wp_unslash($_GET['ec_category'])) : '';
        $url_view     = isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : '';

        // Final values: URL params override shortcode attributes
        $final_category = $url_category !== '' ? $url_category : $sc_category;
        $final_view     = $url_view !== '' ? $url_view : $sc_view;

        // --------------------------------------
        // Build WP_Query arguments
        // --------------------------------------
        $args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'paged'          => $paged,
            's'              => $search,
            'posts_per_page' => $sc_per_page,
        ];

        // Add taxonomy query if category filter set
        if ($final_category) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $final_category,
                ],
            ];
        }

        // --------------------------------------
        // Execute the query
        // --------------------------------------
        $query = new \WP_Query($args);

        // --------------------------------------
        // Output buffering to capture template output
        // --------------------------------------
        ob_start();

        // Locate the template file within plugin or theme
        $template_path = ec_locate_template('ec-archive-event-shortcode.php');

        if ($template_path) {
            // Make variables available to the template
            $final_view = $final_view;
            $query = $query; // ensure $query is available in template
            include $template_path;
        } else {
            echo '<p>' . esc_html__('Event archive template not found.', 'satori-ec') . '</p>';
        }

        // Reset post data after custom query
        wp_reset_postdata();

        // Return buffered output
        return ob_get_clean();
    }
}
