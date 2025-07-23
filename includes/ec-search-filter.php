<?php

/**
 * Search Query Filter for Events Only
 *
 * Ensures that WordPress search queries return only Events when applicable.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit;

// ------------------------------------------------------------------------------
// HOOK: Modify search queries before they are executed
// ------------------------------------------------------------------------------
add_action('pre_get_posts', __NAMESPACE__ . '\\ec_limit_search_to_events_cpt');

/**
 * Restrict WordPress search to only return Event CPT results
 * when searching from the event archive or targeting event CPT explicitly.
 *
 * @param \WP_Query $query The current query instance.
 * @return void
 * @since 1.0.0
 */
function ec_limit_search_to_events_cpt(\WP_Query $query): void
{
    if (
        !is_admin() &&
        $query->is_main_query() &&
        $query->is_search()
    ) {
        $is_event_archive_context = is_post_type_archive('event') || get_query_var('post_type') === 'event';

        if ($is_event_archive_context) {
            $post_type = apply_filters('ec_search_filter_post_type', 'event');
            $query->set('post_type', $post_type);
        }
    }
}
