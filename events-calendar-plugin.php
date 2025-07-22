<?php

/**
 * Plugin Name: Events Calendar Plugin
 * Plugin URI:  https://satori.com.au/
 * Description: A lightweight, modular Events Calendar plugin for WordPress.
 * Version:     1.0.0
 * Author:      Satori Graphics Pty Ltd
 * Author URI:  https://satori.com.au/
 * Text Domain: satori-ec
 */

namespace Satori_EC;

defined('ABSPATH') || exit; // Prevent direct access

// --------------------------------------------------
// Constants
// --------------------------------------------------
define('SATORI_EC_VERSION', '1.0.0');
define('SATORI_EC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SATORI_EC_PLUGIN_URL', plugin_dir_url(__FILE__));

// --------------------------------------------------
// Core Plugin Class
// --------------------------------------------------
final class Plugin
{

    /**
     * Shortcode Manager instance
     *
     * @var Shortcode_Manager|null
     */
    private $shortcode_manager = null;

    /**
     * Constructor – Hooks into WP lifecycle
     */
    public function __construct()
    {
        // Load modular files
        $this->load_includes();

        // Load translations
        add_action('plugins_loaded', [$this, 'load_textdomain']);

        // Register post type
        add_action('init', [$this, 'register_post_type']);

        // Register shortcodes on init hook
        add_action('init', [$this, 'register_shortcodes']);

        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);

        // Modify event archive queries
        add_action('pre_get_posts', [$this, 'filter_events_archive']);
    }

    /**
     * Load all required PHP files
     */
    private function load_includes()
    {
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-form-handler.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-form-fields.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-search-filter.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-excerpt-override.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-session.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-meta-boxes.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-template-loader.php';

        // Shortcodes
        include_once SATORI_EC_PLUGIN_DIR . 'includes/shortcodes/class-ec-archive-shortcode.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/shortcodes/class-ec-shortcode-manager.php';

        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-submission-form.php'; // Optional frontend handler
    }

    /**
     * Load plugin text domain for translations
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'satori-ec',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    /**
     * Register custom post type(s)
     */
    public function register_post_type()
    {
        // TODO: Implement register_event_post_type() or similar
    }

    /**
     * Register shortcodes using the Shortcode Manager class
     */
    public function register_shortcodes()
    {
        if (null === $this->shortcode_manager) {
            $this->shortcode_manager = new Shortcode_Manager();
        }

        // The Shortcode_Manager constructor will handle the registration of all shortcodes,
        // so no need to call any additional methods here.
    }

    /**
     * Register frontend CSS and JavaScript
     */
    public function register_assets()
    {
        // Main stylesheet
        wp_enqueue_style(
            'satori-ec-main',
            SATORI_EC_PLUGIN_URL . 'assets/css/main.css',
            [],
            SATORI_EC_VERSION
        );

        // Toggle/filter JavaScript
        wp_enqueue_script(
            'satori-ec-filter-toggle',
            SATORI_EC_PLUGIN_URL . 'assets/js/filter-toggle.js',
            [],
            SATORI_EC_VERSION,
            true
        );
    }

    /**
     * Modify WP_Query for event archive filtering
     */
    public function filter_events_archive($query)
    {
        if (is_admin() || ! $query->is_main_query()) {
            return;
        }

        if (is_post_type_archive('event') || is_tax('event_category')) {
            // Keyword search
            if (!empty($_GET['s'])) {
                $query->set('s', sanitize_text_field(wp_unslash($_GET['s'])));
            }

            // Category filter via URL param
            if (!empty($_GET['ec_category'])) {
                $query->set('tax_query', [
                    [
                        'taxonomy' => 'event_category',
                        'field'    => 'slug',
                        'terms'    => sanitize_text_field(wp_unslash($_GET['ec_category'])),
                    ]
                ]);
            }

            // Pagination control
            $query->set('posts_per_page', 9);
        }
    }

    /**
     * Activation callback
     */
    public static function activate()
    {
        flush_rewrite_rules();
    }

    /**
     * Deactivation callback
     */
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

// --------------------------------------------------
// Bootstrap Plugin
// --------------------------------------------------
add_action('plugins_loaded', function () {
    new Plugin();
});

// --------------------------------------------------
// Register Lifecycle Hooks
// --------------------------------------------------
register_activation_hook(__FILE__, ['\Satori_EC\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['\Satori_EC\Plugin', 'deactivate']);
