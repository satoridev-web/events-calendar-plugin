<?php

/**
 * EC Archive Shortcode
 *
 * Handles the [ec_event_archive] shortcode for displaying the events archive.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit; // Exit if accessed directly

use WP_Query;

if (!class_exists(__NAMESPACE__ . '\EC_Archive_Shortcode')) {

    final class EC_Archive_Shortcode
    {
        // =============================================================================
        // Register shortcode [ec_event_archive]
        // =============================================================================
        public static function register(): void
        {
            add_shortcode('ec_event_archive', [__CLASS__, 'render_shortcode']);
        }

        // =============================================================================
        // Render the event archive via shortcode
        //
        // @param array<string,mixed> $atts Shortcode attributes.
        // @return string HTML output of the event archive.
        // =============================================================================
        public static function render_shortcode(array $atts): string
        {
            // -------------------------------------------------------------------------
            // Define and sanitize shortcode attributes with defaults
            // -------------------------------------------------------------------------
            $atts = shortcode_atts([
                'category' => '',
                'view'     => 'grid',
                'per_page' => 9,
            ], $atts, 'ec_event_archive');

            $category = sanitize_text_field($atts['category']);
            $view     = sanitize_text_field($atts['view']);
            $per_page = absint($atts['per_page']);

            // -------------------------------------------------------------------------
            // Get URL parameters for filtering & pagination (overrides shortcode attrs)
            // -------------------------------------------------------------------------
            $paged        = max(1, (int) get_query_var('paged', 1));
            $search       = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
            $url_category = isset($_GET['ec_category']) ? sanitize_text_field(wp_unslash($_GET['ec_category'])) : '';
            $url_view     = isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : '';

            // Final values: URL params override shortcode attributes
            $final_category = $url_category !== '' ? $url_category : $category;
            $final_view     = $url_view !== '' ? $url_view : $view;

            // -------------------------------------------------------------------------
            // Build WP_Query arguments
            // -------------------------------------------------------------------------
            $args = [
                'post_type'      => 'event',
                'post_status'    => 'publish',
                'paged'          => $paged,
                's'              => $search,
                'posts_per_page' => $per_page,
            ];

            // Add taxonomy query if category filter set (correct taxonomy slug)
            if (!empty($final_category)) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'event_type', // Use correct taxonomy slug 'event_type'
                        'field'    => 'slug',
                        'terms'    => $final_category,
                    ],
                ];
            }

            // -------------------------------------------------------------------------
            // Execute the query
            // -------------------------------------------------------------------------
            $query = new WP_Query($args);

            // -------------------------------------------------------------------------
            // Output buffering to capture template output
            // -------------------------------------------------------------------------
            ob_start();

            // Load the template via ec_get_template() for future-proofing and overrides
            ec_get_template('ec-archive-event-shortcode.php', [
                'view'  => $final_view,
                'query' => $query,
            ]);

            // Reset post data after custom query
            wp_reset_postdata();

            // Return buffered output
            return ob_get_clean();
        }
    }
}

// =============================================================================
// Register the shortcode early (hook this in your main plugin loader or init)
// =============================================================================
add_action('init', [EC_Archive_Shortcode::class, 'register']);
