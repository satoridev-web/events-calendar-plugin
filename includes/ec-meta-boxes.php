<?php

/**
 * Event Meta Box Registration and Save Logic
 *
 * Adds a custom meta box to the Event CPT for capturing
 * date, time, and location details.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit;

// ------------------------------------------------------------------------------
// ACTION: Register Event Details Meta Box
// ------------------------------------------------------------------------------
add_action('add_meta_boxes', __NAMESPACE__ . '\\ec_add_event_meta_boxes');

/**
 * Register the meta box for event details.
 *
 * @since 1.0.0
 */
function ec_add_event_meta_boxes(): void
{
    add_meta_box(
        'ec_event_details',
        __('Event Details', 'events-calendar-plugin'),
        __NAMESPACE__ . '\\ec_render_event_meta_box',
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
function ec_render_event_meta_box($post): void
{
    $event_date     = get_post_meta($post->ID, '_ec_event_date', true);
    $event_time     = get_post_meta($post->ID, '_ec_event_time', true);
    $event_location = get_post_meta($post->ID, '_ec_event_location', true);

    // Security nonce
    wp_nonce_field('ec_save_event_meta', 'ec_event_meta_nonce');
?>
    <p>
        <label for="ec_event_date"><?php esc_html_e('Event Date:', 'events-calendar-plugin'); ?></label><br>
        <input type="date" id="ec_event_date" name="ec_event_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
    </p>
    <p>
        <label for="ec_event_time"><?php esc_html_e('Event Time:', 'events-calendar-plugin'); ?></label><br>
        <input type="time" id="ec_event_time" name="ec_event_time" value="<?php echo esc_attr($event_time); ?>" class="widefat">
    </p>
    <p>
        <label for="ec_event_location"><?php esc_html_e('Event Location:', 'events-calendar-plugin'); ?></label><br>
        <input type="text" id="ec_event_location" name="ec_event_location" value="<?php echo esc_attr($event_location); ?>" class="widefat">
    </p>
<?php
}

// ------------------------------------------------------------------------------
// ACTION: Save Event Meta Fields
// ------------------------------------------------------------------------------
add_action('save_post', __NAMESPACE__ . '\\ec_save_event_meta');

/**
 * Save custom event meta box data.
 *
 * @param int $post_id The post ID.
 * @return void
 * @since 1.0.0
 */
function ec_save_event_meta(int $post_id): void
{
    if (
        !isset($_POST['ec_event_meta_nonce']) ||
        !wp_verify_nonce($_POST['ec_event_meta_nonce'], 'ec_save_event_meta')
    ) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $meta_fields = [
        'ec_event_date',
        'ec_event_time',
        'ec_event_location',
    ];

    foreach ($meta_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, "_{$field}", sanitize_text_field($_POST[$field]));
        }
    }
}
