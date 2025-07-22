<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Override default excerpt handling for cleaner display in archive/event lists.
 *
 * Version: 1.0.0
 * Author: Satori Graphics
 */

// ===============================
// Customize the excerpt "more" symbol
// ===============================
function ec_custom_excerpt_more($more) {
    return '…'; // Just ellipsis — no link
}
add_filter('excerpt_more', 'ec_custom_excerpt_more', 999);

// ===============================
// Remove theme-injected "Continue reading" links if present
// ===============================
remove_filter('get_the_excerpt', 'twentytwentyfour_excerpt_more');
remove_filter('get_the_excerpt', 'twentytwenty_excerpt_more');

// ===============================
// Custom safe excerpt function
// ===============================
function ec_get_clean_excerpt($post_id = null, $length = 30, $more = '…') {
    $post = get_post($post_id);
    if (!$post) return '';

    // Use manual excerpt if available
    if (has_excerpt($post)) {
        $excerpt = $post->post_excerpt;
    } else {
        $excerpt = wp_strip_all_tags(strip_shortcodes($post->post_content));
    }

    // Use filtered excerpt length for consistency across plugin
    $final_length = apply_filters('ec_excerpt_length', $length);

    $excerpt = wp_trim_words($excerpt, $final_length, $more);

    /**
     * Allow developers to filter the clean excerpt
     */
    return apply_filters('ec_get_clean_excerpt', $excerpt, $post, $final_length, $more);
}
