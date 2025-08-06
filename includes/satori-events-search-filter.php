<?php

/**
 * Search Query Filter for Events Only
 *
 * Ensures that WordPress search queries return only Events when applicable.
 *
 * @package Satori_EC
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

// ------------------------------------------------------------------------------
// HOOK: Modify search queries before they are executed
// ------------------------------------------------------------------------------
add_action('pre_get_posts', __NAMESPACE__ . '\\satori_events_limit_search_to_events_cpt');

/**
 * Restrict WordPress search to only return Event CPT results
 * when searching from the event archive or targeting event CPT explicitly.
 *
 * @param \WP_Query $query The current query instance.
 * @return void
 * @since 1.0.0
 */
function satori_events_limit_search_to_events_cpt(\WP_Query $query): void
{
    if (
        ! is_admin() &&
        $query->is_main_query() &&
        $query->is_search()
    ) {
        $is_event_archive_context = is_post_type_archive('event') || get_query_var('post_type') === 'event';

        if ($is_event_archive_context) {
            $post_type = apply_filters('satori_events_search_filter_post_type', 'event');
            $query->set('post_type', $post_type);

            // Log query if debug mode is enabled
            if (get_option('satori_events_debug_mode') === '1') {
                $log_msg = '[Satori Events] Search filter applied to query: ' . print_r($query->query_vars, true);

                if (function_exists('satori_events_log')) {
                    satori_events_log($log_msg);
                } elseif (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log($log_msg);
                }
            }
        }
    }
}
