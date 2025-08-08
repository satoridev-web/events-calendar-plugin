<?php

/**
 * Plugin Name: SATORI Events
 * Plugin URI:  https://satori.com.au/
 * Description: A lightweight, modular Events plugin for WordPress.
 * Version:     1.0.0
 * Author:      SATORI
 * Author URI:  https://satori.com.au/
 * Text Domain: satori-events
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Plugin Constants
 * -------------------------------------------------*/
define('SATORI_EVENTS_VERSION', '1.0.0');
define('SATORI_EVENTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SATORI_EVENTS_PLUGIN_URL', plugin_dir_url(__FILE__));

/* -------------------------------------------------
 * Load Critical Helpers (Used in Templates)
 * -------------------------------------------------*/
require_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-excerpt-override.php';

/* -------------------------------------------------
 * Core Plugin Class
 * -------------------------------------------------*/
final class Plugin
{
    private $shortcode_manager = null;

    public function __construct()
    {
        $this->load_includes();

        // Instantiate AJAX handler early
        new \Satori_Events\Ajax\Ajax_Handler();

        add_action('plugins_loaded', [$this, 'load_textdomain']);
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('pre_get_posts', [$this, 'filter_events_archive']);
    }

    private function load_includes()
    {
        // Forms
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/forms/satori-events-form-handler.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/forms/satori-events-form-fields.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/forms/satori-events-submission-form.php';

        // Core
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-search-filter.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-session.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-meta-boxes.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-template-loader.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/satori-events-template-functions.php';

        // Admin
        include_once SATORI_EVENTS_PLUGIN_DIR . 'admin/class-satori-events-tools-page.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/ajax/class-satori-events-ajax-handler.php';

        // CPT & Taxonomies
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/post-types/satori-events-register-events-cpt.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/taxonomies/satori-events-register-event-type-taxonomy.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/taxonomies/satori-events-register-event-location-taxonomy.php';

        // Shortcodes
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/shortcodes/class-satori-events-archive-shortcode.php';
        include_once SATORI_EVENTS_PLUGIN_DIR . 'includes/shortcodes/class-satori-events-shortcode-manager.php';
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('satori-events', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    public function register_post_type()
    {
        satori_events_register_event_post_type();
    }

    public function register_shortcodes()
    {
        if (null === $this->shortcode_manager) {
            $this->shortcode_manager = new Shortcode_Manager();
        }
    }

    public function register_assets()
    {
        wp_enqueue_style(
            'satori-events-main',
            SATORI_EVENTS_PLUGIN_URL . 'assets/css/satori-events-main.css',
            [],
            SATORI_EVENTS_VERSION
        );

        wp_enqueue_script(
            'satori-events-filter-toggle',
            SATORI_EVENTS_PLUGIN_URL . 'assets/js/satori-events-filter-toggle.js',
            ['jquery'],
            SATORI_EVENTS_VERSION,
            true
        );
    }

    public function filter_events_archive($query)
    {
        if (function_exists('satori_events_set_event_archive_query')) {
            satori_events_set_event_archive_query($query);
        }
    }

    public static function activate()
    {
        $role = get_role('administrator');
        if ($role && !$role->has_cap('satori_events_tools')) {
            $role->add_cap('satori_events_tools');
        }

        flush_rewrite_rules();
    }

    public static function deactivate()
    {
        flush_rewrite_rules();
    }
}

// --------------------------------------------------
// Init Plugin
// --------------------------------------------------
add_action('plugins_loaded', function () {
    new Plugin();
});

// --------------------------------------------------
// Activation / Deactivation
// --------------------------------------------------
register_activation_hook(__FILE__, ['\Satori_Events\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['\Satori_Events\Plugin', 'deactivate']);
