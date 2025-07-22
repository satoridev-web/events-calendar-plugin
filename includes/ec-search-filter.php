<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Restrict WordPress search to only return Events on the event archive.
 *
 * @param WP_Query $query The current query instance.
 */
function ec_limit_search_to_events_cpt($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Restrict only when search originates from the event archive or explicitly targets event CPT
        if (is_post_type_archive('event') || get_query_var('post_type') === 'event') {
            $query->set('post_type', 'event');
        }
    }
}
add_action('pre_get_posts', 'ec_limit_search_to_events_cpt');
