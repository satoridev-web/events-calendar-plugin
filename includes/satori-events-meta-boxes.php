<?php

/**
 * Event Meta Box Registration and Save Logic
 *
 * Adds a custom meta box to the Event CPT for capturing
 * date, time, and location details.
 *
 * @package Satori_EC
 */

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * ACTION: Register Event Details Meta Box
 * -------------------------------------------------*/
add_action('add_meta_boxes', __NAMESPACE__ . '\\satori_events_add_event_meta_boxes');

/**
 * Register the meta box for event details.
 *
 * @since 1.0.0
 */
function satori_events_add_event_meta_boxes(): void
{
    add_meta_box(
        'satori_events_details',
        __('Event Details', 'events-calendar-plugin'),
        __NAMESPACE__ . '\\satori_events_render_event_meta_box',
        'event',
        'normal',
        'default'
    );
}

/**
 * Render the meta box fields.
 *
 * @param \WP_Post $post The post object.
 * @since 1.0.0
 */
function satori_events_render_event_meta_box(\WP_Post $post): void
{
    $event_date     = get_post_meta($post->ID, '_satori_events_date', true);
    $event_time     = get_post_meta($post->ID, '_satori_events_time', true);
    $event_location = get_post_meta($post->ID, '_satori_events_location', true);

    // Security nonce field
    wp_nonce_field('satori_events_save_event_meta', 'satori_events_meta_nonce');
?>
    <p>
        <label for="satori_events_date"><?php esc_html_e('Event Date:', 'events-calendar-plugin'); ?></label><br>
        <input type="date" id="satori_events_date" name="satori_events_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
    </p>
    <p>
        <label for="satori_events_time"><?php esc_html_e('Event Time:', 'events-calendar-plugin'); ?></label><br>
        <input type="time" id="satori_events_time" name="satori_events_time" value="<?php echo esc_attr($event_time); ?>" class="widefat">
    </p>
    <p>
        <label for="satori_events_location"><?php esc_html_e('Event Location:', 'events-calendar-plugin'); ?></label><br>
        <input type="text" id="satori_events_location" name="satori_events_location" value="<?php echo esc_attr($event_location); ?>" class="widefat">
    </p>
<?php
}

/* -------------------------------------------------
 * ACTION: Save Event Meta Fields
 * -------------------------------------------------*/
add_action('save_post', __NAMESPACE__ . '\\satori_events_save_event_meta');

/**
 * Save custom event meta box data.
 *
 * @param int $post_id The post ID.
 * @since 1.0.0
 */
function satori_events_save_event_meta(int $post_id): void
{
    // Verify nonce for security
    if (
        !isset($_POST['satori_events_meta_nonce']) ||
        !wp_verify_nonce($_POST['satori_events_meta_nonce'], 'satori_events_save_event_meta')
    ) {
        return;
    }

    // Bail if doing autosave or user lacks permission
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Define meta fields and their keys
    $fields = [
        'satori_events_date'     => '_satori_events_date',
        'satori_events_time'     => '_satori_events_time',
        'satori_events_location' => '_satori_events_location',
    ];

    // Sanitize and update meta fields if set
    foreach ($fields as $field_key => $meta_key) {
        if (isset($_POST[$field_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field_key]));
        }
    }
}
