<?php

/**
 * SATORI Events – Template Loader & Query Modifier
 *
 * Applies custom query logic for Event archives including:
 * - Pagination limit
 * - Sorting (title, date)
 * - Filtering by taxonomy
 * - Meta key enforcement
 *
 * @package Satori_Events
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Hook: Modify main event archive query
 * -------------------------------------------------*/
add_action('pre_get_posts', __NAMESPACE__ . '\\satori_events_set_event_archive_query');

/**
 * Modify the main query for event archive page.
 *
 * Applies sorting, filtering, pagination, and ensures only events
 * with the `_satori_events_date` meta key are returned.
 *
 * @param \WP_Query $query The WP_Query instance (passed by reference).
 * @return void
 * @since 1.0.0
 */
function satori_events_set_event_archive_query(\WP_Query $query): void
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {

        /* -------------------------------------------------
		 * Pagination limit (default: 3)
		 * -------------------------------------------------*/
        $query->set('posts_per_page', 3);

        /* -------------------------------------------------
		 * Sorting via URL parameter ?sort=
		 * -------------------------------------------------*/
        $sort = isset($_GET['sort']) ? sanitize_text_field(wp_unslash($_GET['sort'])) : 'date_asc';

        switch ($sort) {
            case 'date_desc':
                $query->set('meta_key', '_satori_events_date');
                $query->set('orderby', 'meta_value');
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
                $query->set('meta_key', '_satori_events_date');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                break;
        }

        /* -------------------------------------------------
		 * Taxonomy filter via ?event_type=
		 * -------------------------------------------------*/
        if (!empty($_GET['event_type'])) {
            $query->set('tax_query', [
                [
                    'taxonomy' => 'event_type',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field(wp_unslash($_GET['event_type'])),
                ],
            ]);
        }

        /* -------------------------------------------------
		 * Meta Query: Ensure _satori_events_date exists
		 * -------------------------------------------------*/
        $meta_query = $query->get('meta_query') ?: [];

        $meta_query[] = [
            'key'     => '_satori_events_date',
            'compare' => 'EXISTS',
        ];

        $query->set('meta_query', $meta_query);

        /* -------------------------------------------------
		 * Debug Logging (if enabled)
		 * -------------------------------------------------*/
        if (function_exists('satori_events_log')) {
            satori_events_log('[Satori Events] Archive query vars: ' . print_r($query->query_vars, true));
        }

        /* -------------------------------------------------
		 * Allow developers to extend logic
		 * -------------------------------------------------*/
        do_action('satori_events_modify_event_archive_query', $query);
    }
}
