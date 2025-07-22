<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/* ================================
   Locate Template with Theme Support
   ================================ */
function ec_locate_template($template_name) {
    $template_paths = array(
        // 1. Look in theme override folder
        get_stylesheet_directory() . '/events-calendar-plugin/' . $template_name,
        // 2. Fallback to plugin templates folder
        plugin_dir_path(__DIR__) . 'templates/' . $template_name,
    );

    foreach ($template_paths as $path) {
        if (file_exists($path)) {
            /**
             * Filter the located template path before returning.
             *
             * @param string $path          The full path to the template.
             * @param string $template_name The requested template name.
             */
            return apply_filters('ec_locate_template', $path, $template_name);
        }
    }

    return false;
}

/* ================================
   Load Custom Templates for Event CPT
   ================================ */
function ec_load_event_templates($template) {
    if (is_singular('event')) {
        $custom_single = ec_locate_template('single-event.php');
        if ($custom_single) {
            return $custom_single;
        }
    }

    if (is_post_type_archive('event')) {
        $custom_archive = ec_locate_template('archive-event.php');
        if ($custom_archive) {
            return $custom_archive;
        }
    }

    return $template;
}
add_filter('template_include', 'ec_load_event_templates');

/* ================================
   Modify Archive Query: Pagination, Sorting, Filtering
   ================================ */
function ec_set_event_archive_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {

        // Set number of posts per page
        $query->set('posts_per_page', 3);

        // Handle sorting via ?sort=
        $sort = $_GET['sort'] ?? 'date_asc';

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

        // Handle filtering via ?event_type=
        if (!empty($_GET['event_type'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['event_type']),
                )
            ));
        }

        /**
         * Allow further customisation via hooks.
         *
         * @param WP_Query $query The modified query object.
         */
        do_action('ec_modify_event_archive_query', $query);
    }
}
add_action('pre_get_posts', 'ec_set_event_archive_query');
