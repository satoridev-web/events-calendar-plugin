<?php
/*
Plugin Name: Events Calendar Plugin
Plugin URI: https://satori.com.au/
Description: A lightweight events calendar with frontend submission support.
Version: 1.0.0
Author: Satori Graphics
Author URI: https://satori.com.au/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: events-calendar-plugin
*/

defined( 'ABSPATH' ) || exit;

// ===============================
// Define constants
// ===============================
define( 'EC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// ===============================
// Load plugin components
// ===============================
require_once EC_PLUGIN_PATH . 'includes/session.php';
require_once EC_PLUGIN_PATH . 'includes/post-types.php';
require_once EC_PLUGIN_PATH . 'includes/taxonomies.php';
require_once EC_PLUGIN_PATH . 'includes/meta-boxes.php';
require_once EC_PLUGIN_PATH . 'includes/form-fields.php';
require_once EC_PLUGIN_PATH . 'includes/submission-form.php';
require_once EC_PLUGIN_PATH . 'includes/form-handler.php';
require_once EC_PLUGIN_PATH . 'includes/excerpt-override.php';
require_once EC_PLUGIN_PATH . 'includes/template-loader.php';
require_once EC_PLUGIN_PATH . 'includes/search-filter.php';
require_once EC_PLUGIN_PATH . 'includes/shortcodes/class-ec-archive-shortcode.php';

// ===============================
// Register custom query vars for shortcodes
// ===============================
function ec_register_shortcode_query_vars( $vars ) {
    $vars[] = 'ec_shortcode_category';
    $vars[] = 'ec_shortcode_view';
    $vars[] = 'ec_shortcode_per_page';
    return $vars;
}
add_filter( 'query_vars', 'ec_register_shortcode_query_vars' );

// ===============================
// Enqueue plugin styles
// ===============================
function ec_enqueue_plugin_styles() {
    global $post;

    if (
        is_singular( 'event' ) ||
        is_post_type_archive( 'event' ) ||
        ( isset( $post ) && has_shortcode( $post->post_content, 'event_submission_form' ) ) ||
        ( isset( $post ) && has_shortcode( $post->post_content, 'ec_event_archive' ) )
    ) {
        wp_enqueue_style(
            'ec-event-styles',
            EC_PLUGIN_URL . 'assets/css/events-calendar-plugin.css',
            [],
            '1.0.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'ec_enqueue_plugin_styles' );

// ===============================
// Customize excerpt length
// ===============================
function ec_custom_excerpt_length( $length ) {
    return apply_filters( 'ec_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'ec_custom_excerpt_length', 999 );

// ===============================
// Register archive shortcode
// ===============================
if ( class_exists( 'EC_Archive_Shortcode' ) ) {
    EC_Archive_Shortcode::register();
}
