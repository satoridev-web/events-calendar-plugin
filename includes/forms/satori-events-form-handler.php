<?php

namespace Satori_Events;

defined('ABSPATH') || exit;

/* -------------------------------------------------
 * Frontend Event Submission Handler
 * -------------------------------------------------*/

/**
 * Handle frontend event submission
 *
 * @return void
 * @since 1.0.0
 */
function satori_events_handle_event_submission(): void
{
    // -------------------------------------------------
    // Only process if submission form was posted
    // -------------------------------------------------
    if (!isset($_POST['satori_events_submit_event'])) {
        return;
    }

    // -------------------------------------------------
    // Optional: Start session if none active (for extensibility)
    // -------------------------------------------------
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // -------------------------------------------------
    // Spam trap honeypot (hidden field)
    // -------------------------------------------------
    if (!empty($_POST['satori_events_event_url'])) {
        return;
    }

    // -------------------------------------------------
    // Security: Verify nonce for form submission
    // -------------------------------------------------
    if (empty($_POST['satori_events_event_nonce']) || !wp_verify_nonce($_POST['satori_events_event_nonce'], 'satori_events_submit_event')) {
        $message = apply_filters(
            'satori_events_message_nonce_failed',
            '<p class="satori-events-error">' . esc_html__('Security check failed. Please try again.', 'satori-events') . '</p>'
        );
        set_transient('satori_events_submission_message', $message, 60);
        return;
    }

    // -------------------------------------------------
    // Validate required fields
    // -------------------------------------------------
    $fields = satori_events_get_event_submission_fields();
    $errors = [];

    foreach ($fields as $name => $field) {
        if (!empty($field['required']) && empty($_POST[$name])) {
            $errors[] = sprintf(esc_html__('%s is required.', 'satori-events'), $field['label']);
        }
    }

    if (!empty($errors)) {
        $message = '<div class="satori-events-error"><p>' . implode('</p><p>', array_map('esc_html', $errors)) . '</p></div>';
        set_transient('satori_events_submission_message', $message, 60);
        return;
    }

    // -------------------------------------------------
    // Insert new event post as draft
    // -------------------------------------------------
    $event_title   = sanitize_text_field($_POST['event_title'] ?? '');
    $event_content = sanitize_textarea_field($_POST['event_description'] ?? '');

    $post_id = wp_insert_post([
        'post_title'   => $event_title,
        'post_content' => $event_content,
        'post_type'    => 'event',
        'post_status'  => 'draft',
    ]);

    if (is_wp_error($post_id) || !$post_id) {
        $message = apply_filters(
            'satori_events_message_post_insert_failed',
            '<p class="satori-events-error">' . esc_html__('Error creating event. Please try again.', 'satori-events') . '</p>'
        );
        set_transient('satori_events_submission_message', $message, 60);
        return;
    }

    // -------------------------------------------------
    // Handle featured image upload (optional)
    // -------------------------------------------------
    if (!empty($_FILES['satori_events_featured_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $file = $_FILES['satori_events_featured_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($file['type'], $allowed_types, true)) {
            $attachment_id = media_handle_upload('satori_events_featured_image', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            }
        }
    }

    // -------------------------------------------------
    // Save custom fields and taxonomies
    // -------------------------------------------------
    foreach ($fields as $name => $field) {
        if ($field['type'] === 'taxonomy' && !empty($_POST[$name])) {
            // Sanitize taxonomy term ID as integer
            wp_set_post_terms($post_id, [(int) $_POST[$name]], $field['taxonomy'], false);
        } elseif (!in_array($name, ['event_title', 'event_description'], true) && isset($_POST[$name])) {
            update_post_meta($post_id, '_satori_events_' . $name, sanitize_text_field($_POST[$name]));
        }
    }

    // -------------------------------------------------
    // Success message and redirect to same page
    // -------------------------------------------------
    $success_message = apply_filters(
        'satori_events_message_submission_success',
        '<p class="satori-events-success">' . esc_html__('Event submitted successfully! It will be reviewed by an admin.', 'satori-events') . '</p>'
    );

    set_transient('satori_events_submission_message', $success_message, 60);

    wp_safe_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
    exit;
}

// ------------------------------------------------------------------------------
// Hook into 'wp' action to process submission early on frontend
// ------------------------------------------------------------------------------
add_action('wp', __NAMESPACE__ . '\\satori_events_handle_event_submission');
