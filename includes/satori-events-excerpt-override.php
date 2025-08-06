<?php

/**
 * Custom Excerpt Overrides for Events Calendar Plugin
 *
 * Provides cleaner, minimal excerpts without theme interference.
 *
 * @package Satori_EC
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * FILTER: Replace default excerpt "more" symbol
 * -------------------------------------------------*/
add_filter('excerpt_more', __NAMESPACE__ . '\\satori_events_custom_excerpt_more', 999);

/**
 * Replace default excerpt "read more" text with simple ellipsis.
 *
 * @param string $more Original more string.
 * @return string Modified more string.
 */
function satori_events_custom_excerpt_more(string $more): string
{
    return '…';
}

/* -------------------------------------------------
 * REMOVE: Theme-specific excerpt filters (if applied and exist)
 * -------------------------------------------------*/
if (function_exists('twentytwentyfour_excerpt_more')) {
    remove_filter('get_the_excerpt', 'twentytwentyfour_excerpt_more');
}

if (function_exists('twentytwenty_excerpt_more')) {
    remove_filter('get_the_excerpt', 'twentytwenty_excerpt_more');
}

/* -------------------------------------------------
 * FUNCTION: Generate clean, safe excerpt
 * -------------------------------------------------*/
/**
 * Return a clean excerpt for a given post ID, with safe fallbacks.
 *
 * @param int|null $post_id The post ID. Defaults to global post.
 * @param int $length Word count for excerpt.
 * @param string $more More symbol or string.
 * @return string Clean trimmed excerpt.
 */
function satori_events_get_clean_excerpt(?int $post_id = null, int $length = 30, string $more = '…'): string
{
    $post = $post_id ? get_post($post_id) : get_post();
    if (!$post instanceof \WP_Post) {
        return '';
    }

    // Use manually set excerpt if available
    $excerpt = has_excerpt($post)
        ? $post->post_excerpt
        : wp_strip_all_tags(strip_shortcodes($post->post_content));

    $final_length = apply_filters('satori_events_excerpt_length', $length);

    $trimmed = wp_trim_words($excerpt, $final_length, $more);

    /**
     * FILTER: Allow 3rd parties to modify the final excerpt.
     *
     * @param string   $trimmed      The final excerpt.
     * @param WP_Post  $post         The post object.
     * @param int      $final_length Number of words.
     * @param string   $more         More symbol.
     */
    return apply_filters('satori_events_get_clean_excerpt', $trimmed, $post, $final_length, $more);
}
