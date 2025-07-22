<?php

/**
 * Plugin Name: Events Calendar Plugin
 * Description: A lightweight, modular Events Calendar plugin for WordPress.
 * Version: 1.0.0
 * Author: Satori Graphics Pty Ltd
 * Text Domain: satori-ec
 */

defined('ABSPATH') || exit; // Exit if accessed directly

// ----------------------------------------------
// Define constants
// ----------------------------------------------
define('SATORI_EC_VERSION', '1.0.0');
define('SATORI_EC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SATORI_EC_PLUGIN_URL', plugin_dir_url(__FILE__));

// ----------------------------------------------
// Namespace and main plugin class
// ----------------------------------------------
namespace Satori_EC;

final class Plugin
{

    // ------------------------------------------
    // Run plugin setup on init
    // ------------------------------------------
    public function __construct()
    {
        // Load core includes
        $this->load_includes();

        // Hook into init or other WordPress actions as needed
        add_action('init', [$this, 'register_post_type']);

        // Register assets
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
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
