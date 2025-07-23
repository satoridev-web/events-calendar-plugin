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

defined('ABSPATH') || exit; // Exit if accessed directly

// --------------------------------------------------
// Constants
// --------------------------------------------------
define('SATORI_EC_VERSION', '1.0.0');
define('SATORI_EC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SATORI_EC_PLUGIN_URL', plugin_dir_url(__FILE__));

// --------------------------------------------------
// Load critical helpers early — used in templates
// --------------------------------------------------
require_once SATORI_EC_PLUGIN_DIR . 'includes/ec-excerpt-override.php'; // <== moved here

// --------------------------------------------------
// Core Plugin Class
// --------------------------------------------------
final class Plugin
{
    /**
     * @var Shortcode_Manager|null Instance of shortcode manager
     */
    private $shortcode_manager = null;

    /**
     * Constructor — Setup hooks and initialisation
     */
    public function __construct()
    {
        // Load core files
        $this->load_includes();

        // Internationalisation
        add_action('plugins_loaded', [$this, 'load_textdomain']);

        // Register post types & shortcodes
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_shortcodes']);

        // Register frontend assets
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);

        // Filter archive queries
        add_action('pre_get_posts', [$this, 'filter_events_archive']);
    }

    // --------------------------------------------------
    // Load required plugin files
    // --------------------------------------------------
    private function load_includes()
    {
        // --------------------------------------------------
        // Form handling
        // --------------------------------------------------
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-form-handler.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-form-fields.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/forms/ec-submission-form.php';

        // --------------------------------------------------
        // Core functionality
        // --------------------------------------------------
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-search-filter.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-session.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-meta-boxes.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-template-loader.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/ec-template-functions.php';

        // --------------------------------------------------
        // Post types and taxonomies
        // --------------------------------------------------
        include_once SATORI_EC_PLUGIN_DIR . 'includes/post-types/ec-register-events-cpt.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/taxonomies/ec-register-event-type-taxonomy.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/taxonomies/ec-register-event-location-taxonomy.php';

        // --------------------------------------------------
        // Shortcodes
        // --------------------------------------------------
        include_once SATORI_EC_PLUGIN_DIR . 'includes/shortcodes/class-ec-archive-shortcode.php';
        include_once SATORI_EC_PLUGIN_DIR . 'includes/shortcodes/class-ec-shortcode-manager.php';
    }

    // --------------------------------------------------
    // Load plugin textdomain for translation support
    // --------------------------------------------------
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'satori-ec',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    // --------------------------------------------------
    // Register custom post type(s)
    // --------------------------------------------------
    public function register_post_type()
    {
        ec_register_event_post_type();
    }

    // --------------------------------------------------
    // Register shortcodes via manager
    // --------------------------------------------------
    public function register_shortcodes()
    {
        if (null === $this->shortcode_manager) {
            $this->shortcode_manager = new Shortcode_Manager();
        }
    }

    // --------------------------------------------------
    // Enqueue frontend assets
    // --------------------------------------------------
    public function register_assets()
    {
        // Core CSS
        wp_enqueue_style(
            'satori-ec-main',
            SATORI_EC_PLUGIN_URL . 'assets/css/events-calendar-plugin.css',
            [],
            SATORI_EC_VERSION
        );

        // Frontend filter toggle JS
        wp_enqueue_script(
            'satori-ec-filter-toggle',
            SATORI_EC_PLUGIN_URL . 'assets/js/filter-toggle.js',
            [],
            SATORI_EC_VERSION,
            true
        );
    }

    // --------------------------------------------------
    // Modify main archive queries to support filtering
    // --------------------------------------------------
    public function filter_events_archive($query)
    {
        if (is_admin() || ! $query->is_main_query()) {
            return;
        }

        if (is_post_type_archive('event') || is_tax('event_type') || is_tax('event_location')) {
            // Search filter
            if (!empty($_GET['s'])) {
                $query->set('s', sanitize_text_field(wp_unslash($_GET['s'])));
            }

            // Taxonomy filter via ?ec_category
            if (!empty($_GET['ec_category'])) {
                $query->set('tax_query', [
                    [
                        'taxonomy' => 'event_type',
                        'field'    => 'slug',
                        'terms'    => sanitize_text_field(wp_unslash($_GET['ec_category'])),
                    ],
                ]);
            }

            // Location filter via ?ec_location
            if (!empty($_GET['ec_location'])) {
                $existing_tax_query = $query->get('tax_query') ?: [];

                $existing_tax_query[] = [
                    'taxonomy' => 'event_location',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field(wp_unslash($_GET['ec_location'])),
                ];

                $query->set('tax_query', $existing_tax_query);
            }

            // Limit archive posts
            $query->set('posts_per_page', 3);
        }
    }

    // --------------------------------------------------
    // Plugin activation callback
    // --------------------------------------------------
    public static function activate()
    {
        flush_rewrite_rules();
    }

    // --------------------------------------------------
    // Plugin deactivation callback
    // --------------------------------------------------
    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

// --------------------------------------------------
// Initialise plugin after plugins_loaded
// --------------------------------------------------
add_action('plugins_loaded', function () {
    new Plugin();
});

// --------------------------------------------------
// Register lifecycle hooks
// --------------------------------------------------
register_activation_hook(__FILE__, ['\Satori_EC\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['\Satori_EC\Plugin', 'deactivate']);
