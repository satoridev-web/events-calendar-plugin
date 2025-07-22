<?php
// =============================================================================
// TEMPLATE LOADER: ec-template-loader.php
// PURPOSE: Handles template overrides and archive query customization
// AUTHOR: Satori Graphics Pty Ltd
// =============================================================================

defined('ABSPATH') || exit;

/* =============================================================================
   EC Locate Template
   -----------------------------------------------------------------------------
   Locate template files in the following order:
   1. Theme override: /your-theme/events-calendar-plugin/{template-name}
   2. Plugin fallback: /wp-content/plugins/events-calendar-plugin/templates/
   ============================================================================= */
function ec_locate_template($template_name)
{
    $template_paths = array(
        get_stylesheet_directory() . '/events-calendar-plugin/' . $template_name,
        plugin_dir_path(__DIR__) . 'templates/' . $template_name,
    );

    foreach ($template_paths as $path) {
        if (file_exists($path)) {
            return apply_filters('ec_locate_template', $path, $template_name);
        }
    }

    return false;
}

/* =============================================================================
   EC Load Event Templates
   -----------------------------------------------------------------------------
   Intercepts template loading for:
   - Single event posts
   - Event archive
   ============================================================================= */
function ec_load_event_templates($template)
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

/* =============================================================================
   EC Modify Event Archive Query
   -----------------------------------------------------------------------------
   Customizes the main query for the 'event' archive:
   - Posts per page
   - Sorting (via `?sort=`)
   - Filtering by taxonomy (via `?event_type=`)
   ============================================================================= */
function ec_set_event_archive_query($query)
{
    if (! is_admin() && $query->is_main_query() && is_post_type_archive('event')) {

        // --------------------------------
        // Pagination limit (default: 3)
        // --------------------------------
        $query->set('posts_per_page', 3);

        // --------------------------------
        // Sorting: via URL ?sort=
        // --------------------------------
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_asc';

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

        // --------------------------------
        // Taxonomy filter: via ?event_type=
        // --------------------------------
        if (! empty($_GET['event_type'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['event_type']),
                ),
            ));
        }

        // --------------------------------
        // Developer hook
        // --------------------------------
        do_action('ec_modify_event_archive_query', $query);
    }
}
add_action('pre_get_posts', 'ec_set_event_archive_query');
