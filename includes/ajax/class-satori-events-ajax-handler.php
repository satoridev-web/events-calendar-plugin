<?php

namespace Satori_Events\Ajax;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * SATORI Events AJAX Handler
 * -------------------------------------------------*/

class Ajax_Handler
{
    /**
     * Constructor: add AJAX hooks
     */
    public function __construct()
    {
        add_action('wp_ajax_satori_events_ajax_refresh_metadata', [$this, 'ajax_refresh_metadata']);
        add_action('wp_ajax_satori_events_ajax_clear_cache', [$this, 'ajax_clear_cache']);
    }

    /**
     * AJAX: Refresh Metadata for all published events
     */
    public function ajax_refresh_metadata()
    {
        // Check nonce and user capability
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'satori_events_ajax_nonce')) {
            wp_send_json_error(__('Invalid nonce.', 'satori-events'));
        }

        if (! current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'satori-events'));
        }

        $args = [
            'post_type'      => 'event',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];

        $events = get_posts($args);

        foreach ($events as $event) {
            $event_id = $event->ID;

            $date     = get_post_meta($event_id, '_satori_events_date', true);
            $time     = get_post_meta($event_id, '_satori_events_time', true);
            $location = get_post_meta($event_id, '_satori_events_location', true);

            if ($date) {
                update_post_meta($event_id, '_satori_events_date', $date);
            }

            if ($time) {
                update_post_meta($event_id, '_satori_events_time', $time);
            }

            if ($location) {
                update_post_meta($event_id, '_satori_events_location', $location);
            }
        }

        wp_send_json_success(__('Event metadata refreshed successfully.', 'satori-events'));
    }

    /**
     * AJAX: Clear plugin cache
     */
    public function ajax_clear_cache()
    {
        // Check nonce and user capability
        if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'satori_events_ajax_nonce')) {
            wp_send_json_error(__('Invalid nonce.', 'satori-events'));
        }

        if (! current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'satori-events'));
        }

        wp_cache_flush();

        wp_send_json_success(__('Event cache cleared successfully.', 'satori-events'));
    }
}
