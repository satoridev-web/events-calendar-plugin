<?php
defined( 'ABSPATH' ) || exit;

class EC_Archive_Shortcode {

    public static function register() {
        add_shortcode( 'ec_event_archive', [ __CLASS__, 'render_shortcode' ] );
    }

    public static function render_shortcode( $atts ) {
        // Default shortcode attributes
        $atts = shortcode_atts([
            'category'  => '',
            'view'      => 'grid',
            'per_page'  => 9,
        ], $atts, 'ec_event_archive' );

        // Sanitize attributes
        $sc_category  = sanitize_text_field( $atts['category'] );
        $sc_view      = sanitize_text_field( $atts['view'] );
        $sc_per_page  = absint( $atts['per_page'] );

        // Handle pagination
        $paged = max( 1, get_query_var( 'paged', 1 ) );

        // Override from URL filters
        $search       = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
        $url_category = isset( $_GET['ec_category'] ) ? sanitize_text_field( $_GET['ec_category'] ) : '';
        $url_view     = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : '';

        // Final values (URL overrides shortcode values)
        $final_category = $url_category ? $url_category : $sc_category;
        $final_view     = $url_view ? $url_view : $sc_view;

        // Query args
        $args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'paged'          => $paged,
            's'              => $search,
            'posts_per_page' => $sc_per_page,
        ];

        if ( $final_category ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $final_category,
                ],
            ];
        }

        $query = new WP_Query( $args );

        // Output buffering
        ob_start();

        // Load the archive template
        $template_path = ec_locate_template( 'archive-event-shortcode.php' );
        if ( $template_path ) {
            // Pass only what's needed
            $final_view = $final_view; // Ensures var name consistency
            include $template_path;
        } else {
            echo '<p>' . esc_html__( 'Event archive template not found.', 'events-calendar-plugin' ) . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}
