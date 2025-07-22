<?php

namespace Satori_EC;

defined('ABSPATH') || exit; // Exit if accessed directly

/**
 * Plugin Name: Events Calendar Plugin
 * Description: A lightweight, modular Events Calendar plugin for WordPress.
 * Version: 1.0.0
 * Author: Satori Graphics Pty Ltd
 * Text Domain: satori-ec
 */

// ----------------------------------------------
// Define constants
// ----------------------------------------------
define('SATORI_EC_VERSION', '1.0.0');
define('SATORI_EC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SATORI_EC_PLUGIN_URL', plugin_dir_url(__FILE__));

// ----------------------------------------------
// Main plugin class
// ----------------------------------------------
final class Plugin
{

    // ------------------------------------------
    // Run plugin setup on init
    // ------------------------------------------
    public function __construct()
    {
        // Load core includes
        $this->load_includes();

        // Load translations
        add_action('plugins_loaded', [$this, 'load_textdomain']);

        // Hook into init or other WordPress actions as needed
        add_action('init', [$this, 'register_post_type']);

        // Register assets
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);

        // Hook into pre_get_posts for archive filtering
        add_action('pre_get_posts', [$this, 'filter_events_archive']);
    }

    // ------------------------------------------
    // Load modular files
    // ------------------------------------------
    private function load_includes()
    {
        include_once SATORI_EC_PLUGIN_DIR . 'includes/shortcodes/class-ec-archive-shortcode.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-form-handler.php';
        // Add more includes here as needed
    }

    // ------------------------------------------
    // Load plugin textdomain for translations
    // ------------------------------------------
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'satori-ec', // Text domain
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    // ------------------------------------------
    // Register custom post type (example stub)
    // ------------------------------------------
    public function register_post_type()
    {
        // Optional: register_event_post_type() or other logic
    }

    // ------------------------------------------
    // Register CSS/JS assets
    // ------------------------------------------
    public function register_assets()
    {
        // ----------------------------------------
        // Enqueue main stylesheet with versioning
        // ----------------------------------------
        wp_enqueue_style(
            'satori-ec-main',
            SATORI_EC_PLUGIN_URL . 'assets/css/main.css',
            [],
            SATORI_EC_VERSION
        );

        // ----------------------------------------
        // Enqueue frontend filter & toggle JS
        // ----------------------------------------
        wp_enqueue_script(
            'satori-ec-filter-toggle',
            SATORI_EC_PLUGIN_URL . 'assets/js/filter-toggle.js',
            [], // No dependencies since vanilla JS
            SATORI_EC_VERSION,
            true // Load in footer
        );
    }

    // ------------------------------------------
    // Filter the main query for events archive pages
    // ------------------------------------------
    public function filter_events_archive($query)
    {
        // Bail early if in admin or not main query
        if (is_admin() || ! $query->is_main_query()) {
            return;
        }

        // Check if we are on the event archive or event category taxonomy
        if (is_post_type_archive('event') || is_tax('event_category')) {

            // Search filter from URL
            if (isset($_GET['s']) && ! empty($_GET['s'])) {
                $query->set('s', sanitize_text_field(wp_unslash($_GET['s'])));
            }

            // Category filter from URL parameter ec_category
            if (isset($_GET['ec_category']) && ! empty($_GET['ec_category'])) {
                $tax_query = [
                    [
                        'taxonomy' => 'event_category',
                        'field'    => 'slug',
                        'terms'    => sanitize_text_field(wp_unslash($_GET['ec_category'])),
                    ],
                ];
                $query->set('tax_query', $tax_query);
            }

            // Set posts per page, adjust if you want to make dynamic
            $query->set('posts_per_page', 9);

            // Optional: add orderby, order etc. here if needed
        }
    }

    // ------------------------------------------
    // Plugin activation routine
    // ------------------------------------------
    public static function activate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    // ------------------------------------------
    // Plugin deactivation routine (optional)
    // ------------------------------------------
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

// ----------------------------------------------
// Instantiate the plugin
// ----------------------------------------------
add_action('plugins_loaded', function () {
    new Plugin();
});

// ----------------------------------------------
// Register activation & deactivation hooks
// ----------------------------------------------
register_activation_hook(__FILE__, ['\Satori_EC\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['\Satori_EC\Plugin', 'deactivate']);
