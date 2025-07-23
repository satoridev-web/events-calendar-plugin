<?php

/**
 * Template Loader
 *
 * Handles template overrides and archive query customization.
 *
 * @package Satori_EC
 */

defined('ABSPATH') || exit;

// =============================================================================
// Locate template files in child theme, parent theme, or plugin fallback.
// Developers may override using the 'ec_template_path' filter.
// Caches located templates during request for performance.
// =============================================================================
/**
 * Locate a template file.
 *
 * @param string $template_name Filename of the template to locate.
 * @return string|false Full path to the template or false if not found.
 */
function ec_locate_template(string $template_name): string|false
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
            $cache[$template_name] = apply_filters('ec_template_path', $path, $template_name);
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
    do_action('ec_template_missing', $template_name);

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
function ec_load_event_templates(string $template): string
{
    if (is_singular('event')) {
        $custom_single = ec_locate_template('ec-single-event.php');
        if ($custom_single) {
            return $custom_single;
        }
    }

    if (is_post_type_archive('event')) {
        $custom_archive = ec_locate_template('ec-archive-event.php');
        if ($custom_archive) {
            return $custom_archive;
        }
    }

    return $template;
}
add_filter('template_include', 'ec_load_event_templates');

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
function ec_set_event_archive_query(\WP_Query $query): void
{
    if (! is_admin() && $query->is_main_query() && is_post_type_archive('event')) {

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
                $query->set('meta_key', '_ec_event_date');
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
                $query->set('meta_key', '_ec_event_date');
                $query->set('order', 'ASC');
                break;
        }

        // -------------------------------------------------------------------------
        // Taxonomy filter via URL parameter ?event_type=
        // -------------------------------------------------------------------------
        if (! empty($_GET['event_type'])) {
            $query->set('tax_query', [
                [
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field(wp_unslash($_GET['event_type'])),
                ],
            ]);
        }

        // -------------------------------------------------------------------------
        // Hook for further customization by developers
        // -------------------------------------------------------------------------
        do_action('ec_modify_event_archive_query', $query);
    }
}
add_action('pre_get_posts', 'ec_set_event_archive_query');
