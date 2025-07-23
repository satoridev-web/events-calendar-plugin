<?php

/**
 * Frontend Event Submission Handler
 *
 * Processes the submitted event form on the frontend.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit;

// ------------------------------------------------------------------------------
// HOOK: Trigger handler on 'wp' (safe and early frontend action)
// ------------------------------------------------------------------------------
add_action('wp', __NAMESPACE__ . '\\ec_handle_event_submission');

/**
 * Handle frontend event submission
 *
 * @return void
 * @since 1.0.0
 */
function ec_handle_event_submission(): void
{
    // ------------------------------------------------------------------------------
    // CHECK: Only handle form submission if expected submit button is set
    // ------------------------------------------------------------------------------
    if (!isset($_POST['ec_submit_event'])) {
        return;
    }

    // ------------------------------------------------------------------------------
    // OPTIONAL: Start session if not already active (fallback for extensions)
    // ------------------------------------------------------------------------------
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // ------------------------------------------------------------------------------
    // SPAM TRAP: Honeypot field (should be hidden by CSS)
    // ------------------------------------------------------------------------------
    if (!empty($_POST['ec_event_url'])) {
        return;
    }

    // ------------------------------------------------------------------------------
    // SECURITY: Verify nonce
    // ------------------------------------------------------------------------------
    if (empty($_POST['ec_event_nonce']) || !wp_verify_nonce($_POST['ec_event_nonce'], 'ec_submit_event')) {
        $message = apply_filters('ec_message_nonce_failed', '<p class="ec-error">Security check failed. Please try again.</p>');
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // ------------------------------------------------------------------------------
    // VALIDATE: Required fields
    // ------------------------------------------------------------------------------
    $fields = ec_get_event_submission_fields();
    $errors = [];

    foreach ($fields as $name => $field) {
        if (!empty($field['required']) && empty($_POST[$name])) {
            $errors[] = sprintf(__('%s is required.', 'events-calendar-plugin'), $field['label']);
        }
    }

    if (!empty($errors)) {
        $message = '<div class="ec-error"><p>' . implode('</p><p>', array_map('esc_html', $errors)) . '</p></div>';
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // ------------------------------------------------------------------------------
    // INSERT: Sanitize fields + create event post (as draft)
    // ------------------------------------------------------------------------------
    $event_title   = sanitize_text_field($_POST['event_title']);
    $event_content = sanitize_textarea_field($_POST['event_description']);

    $post_id = wp_insert_post([
        'post_title'   => $event_title,
        'post_content' => $event_content,
        'post_type'    => 'event',
        'post_status'  => 'draft',
    ]);

    if (is_wp_error($post_id) || !$post_id) {
        $message = apply_filters('ec_message_post_insert_failed', '<p class="ec-error">Error creating event. Please try again.</p>');
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // ------------------------------------------------------------------------------
    // IMAGE: Handle featured image upload (optional)
    // ------------------------------------------------------------------------------
    if (!empty($_FILES['ec_featured_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $file = $_FILES['ec_featured_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($file['type'], $allowed_types, true)) {
            $attachment_id = media_handle_upload('ec_featured_image', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            }
        }
    }

    // ------------------------------------------------------------------------------
    // META: Save custom fields and taxonomies
    // ------------------------------------------------------------------------------
    foreach ($fields as $name => $field) {
        if ($field['type'] === 'taxonomy' && !empty($_POST[$name])) {
            wp_set_post_terms($post_id, [(int) $_POST[$name]], $field['taxonomy'], false);
        } elseif (!in_array($name, ['event_title', 'event_description'], true) && isset($_POST[$name])) {
            update_post_meta($post_id, '_ec_' . $name, sanitize_text_field($_POST[$name]));
        }
    }

    // ------------------------------------------------------------------------------
    // SUCCESS: Notify user and redirect
    // ------------------------------------------------------------------------------
    $success_message = apply_filters(
        'ec_message_submission_success',
        '<p class="ec-success">Event submitted successfully! It will be reviewed by an admin.</p>'
    );

    set_transient('ec_submission_message', $success_message, 60);

    wp_safe_redirect(esc_url_raw($_SERVER['REQUEST_URI']));
    exit;
}
