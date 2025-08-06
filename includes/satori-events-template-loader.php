<?php

/**
 * Template Loader
 *
 * Handles template overrides and archive query customization.
 *
 * @package Satori_EC
 */

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Utility: Conditional Logging
 * -------------------------------------------------*/

/**
 * Log messages to error log if debug mode is enabled.
 *
 * @param string $message The message to log.
 * @return void
 */
function satori_events_log(string $message): void
{
    if (get_option('satori_events_debug_mode') === '1') {
        error_log('[Satori Events] ' . $message);
    }
}

// =============================================================================
// Locate template files in child theme, parent theme, or plugin fallback.
// Developers may override using the 'satori_events_template_path' filter.
// Caches located templates during request for performance.
// =============================================================================

/**
 * Locate a template file.
 *
 * @param string $template_name Filename of the template to locate.
 * @return string|false Full path to the template or false if not found.
 */
function satori_events_locate_template(string $template_name): string|false
{
    static $cache = [];

    if (isset($cache[$template_name])) {
        return $cache[$template_name];
    }

    $paths = [
        get_stylesheet_directory() . '/events-calendar-plugin/' . $template_name, // Child theme
        get_template_directory()   . '/events-calendar-plugin/' . $template_name, // Parent theme
        plugin_dir_path(__DIR__)   . 'templates/' . $template_name,               // Plugin fallback
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            $cache[$template_name] = apply_filters('satori_events_template_path', $path, $template_name);

            satori_events_log("Template located: {$cache[$template_name]}");

            return $cache[$template_name];
        }
    }

    // Cache false to avoid repeated file checks
    $cache[$template_name] = false;

    /**
     * Action hook fired when a template is missing.
     *
     * @param string $template_name Name of the missing template.
     */
    do_action('satori_events_template_missing', $template_name);

    satori_events_log("Template missing: {$template_name}");

    return false;
}

// =============================================================================
// Intercept template loading for single event posts and event archive.
// Allows theme/plugin overrides.
// =============================================================================

/**
 * Load custom templates for event post types.
 *
 * @param string $template The path to the current template.
 * @return string Modified template path if override found, otherwise original.
 */
function satori_events_load_event_templates(string $template): string
{
    if (is_singular('event')) {
        $custom_single = satori_events_locate_template('satori-events-single-event.php');
        if ($custom_single) {
            satori_events_log("Using custom single event template: $custom_single");
            return $custom_single;
        }
    }

    if (is_post_type_archive('event')) {
        $custom_archive = satori_events_locate_template('satori-events-archive-event.php');
        if ($custom_archive) {
            satori_events_log("Using custom event archive template: $custom_archive");
            return $custom_archive;
        }
    }

    return $template;
}
add_filter('template_include', 'satori_events_load_event_templates');

// =============================================================================
// Customize the main query for the 'event' archive:
// - Set posts per page limit
// - Sorting via ?sort= parameter
// - Taxonomy filtering via ?event_type=
// =============================================================================

/**
 * Modify the main query for event archive page.
 *
 * @param \WP_Query $query The WP_Query instance (passed by reference).
 * @return void
 */
function satori_events_set_event_archive_query(\WP_Query $query): void
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {

        // -------------------------------------------------------------------------
        // Pagination limit (default: 3)
        // -------------------------------------------------------------------------
        $query->set('posts_per_page', 3);

        // -------------------------------------------------------------------------
        // Sorting via URL parameter ?sort=
        // -------------------------------------------------------------------------
        $sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : 'date_asc';

        switch ($sort) {
            case 'date_desc':
                $query->set('orderby', 'meta_value');
                $query->set('meta_key', '_satori_events_date');
                $query->set('order', 'DESC');
                break;

            case 'title_asc':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;

            case 'title_desc':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;

            case 'date_asc':
            default:
                $query->set('orderby', 'meta_value');
                $query->set('meta_key', '_satori_events_date');
                $query->set('order', 'ASC');
                break;
        }

        // -------------------------------------------------------------------------
        // Taxonomy filter via URL parameter ?event_type=
        // -------------------------------------------------------------------------
        if (!empty($_GET['event_type'])) {
            $query->set('tax_query', [
                [
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field(wp_unslash($_GET['event_type'])),
                ],
            ]);
        }

        satori_events_log('Event archive query args: ' . print_r($query->query_vars, true));

        // -------------------------------------------------------------------------
        // Hook for further customization by developers
        // -------------------------------------------------------------------------
        do_action('satori_events_modify_event_archive_query', $query);
    }
}
add_action('pre_get_posts', 'satori_events_set_event_archive_query');
