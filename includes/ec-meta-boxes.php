<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Register the meta box for event details
 */
function ec_add_event_meta_boxes() {
    add_meta_box(
        'ec_event_details', // ID
        __('Event Details', 'events-calendar-plugin'), // Title
        'ec_render_event_meta_box', // Callback
        'event', // Post type
        'normal', // Context
        'default' // Priority
    );
}
add_action('add_meta_boxes', 'ec_add_event_meta_boxes');

/**
 * Render the meta box content
 */
function ec_render_event_meta_box($post) {
    // Retrieve existing values
    $event_date = get_post_meta($post->ID, '_ec_event_date', true);
    $event_time = get_post_meta($post->ID, '_ec_event_time', true);
    $event_location = get_post_meta($post->ID, '_ec_event_location', true);

    // Nonce field for security
    wp_nonce_field('ec_save_event_meta', 'ec_event_meta_nonce');
    ?>
    <p>
        <label for="ec_event_date"><?php _e('Event Date:', 'events-calendar-plugin'); ?></label><br>
        <input type="date" id="ec_event_date" name="ec_event_date" value="<?php echo esc_attr($event_date); ?>" class="widefat">
    </p>
    <p>
        <label for="ec_event_time"><?php _e('Event Time:', 'events-calendar-plugin'); ?></label><br>
        <input type="time" id="ec_event_time" name="ec_event_time" value="<?php echo esc_attr($event_time); ?>" class="widefat">
    </p>
    <p>
        <label for="ec_event_location"><?php _e('Event Location:', 'events-calendar-plugin'); ?></label><br>
        <input type="text" id="ec_event_location" name="ec_event_location" value="<?php echo esc_attr($event_location); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save the meta box data
 */
function ec_save_event_meta($post_id) {
    // Verify nonce
    if (!isset($_POST['ec_event_meta_nonce']) || !wp_verify_nonce($_POST['ec_event_meta_nonce'], 'ec_save_event_meta')) {
        return;
    }

    // Autosave & permissions check
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save each field safely
    if (isset($_POST['ec_event_date'])) {
        update_post_meta($post_id, '_ec_event_date', sanitize_text_field($_POST['ec_event_date']));
    }
    if (isset($_POST['ec_event_time'])) {
        update_post_meta($post_id, '_ec_event_time', sanitize_text_field($_POST['ec_event_time']));
    }
    if (isset($_POST['ec_event_location'])) {
        update_post_meta($post_id, '_ec_event_location', sanitize_text_field($_POST['ec_event_location']));
    }
}
add_action('save_post', 'ec_save_event_meta');