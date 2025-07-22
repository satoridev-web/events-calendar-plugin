<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

// Start session if needed (optional fallback for future upgrades)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Handle frontend event submission.
 */
function ec_handle_event_submission() {
    if (!isset($_POST['ec_submit_event'])) {
        // error_log('[EC Debug] Form not submitted — skipping handler.');
        return;
    }

    // error_log('[EC Debug] Form submitted.');

    // Honeypot check
    if (!empty($_POST['ec_event_url'])) {
        // error_log('[EC Debug] Honeypot triggered — likely spam, aborting.');
        return;
    }

    // Verify nonce
    if (empty($_POST['ec_event_nonce']) || !wp_verify_nonce($_POST['ec_event_nonce'], 'ec_submit_event')) {
        $message = apply_filters('ec_message_nonce_failed', '<p class="ec-error">Security check failed. Please try again.</p>');
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // error_log('[EC Debug] Nonce verified.');

    $fields = ec_get_event_submission_fields();
    $errors = [];

    // Validate fields
    foreach ($fields as $name => $field) {
        if (!empty($field['required']) && empty($_POST[$name])) {
            $errors[] = sprintf(__('%s is required.', 'events-calendar-plugin'), $field['label']);
            // error_log("[EC Debug] Validation error: {$field['label']} is required.");
        }
    }

    if (!empty($errors)) {
        $message = '<div class="ec-error"><p>' . implode('</p><p>', array_map('esc_html', $errors)) . '</p></div>';
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // error_log('[EC Debug] Validation passed.');

    // Sanitize + insert post
    $event_title   = sanitize_text_field($_POST['event_title']);
    $event_content = sanitize_textarea_field($_POST['event_description']);

    $post_id = wp_insert_post([
        'post_title'   => $event_title,
        'post_content' => $event_content,
        'post_type'    => 'event',
        'post_status'  => 'draft',
    ]);

    if (is_wp_error($post_id) || !$post_id) {
        $error_message = is_wp_error($post_id) ? $post_id->get_error_message() : 'Unknown error';
        $message = apply_filters('ec_message_post_insert_failed', '<p class="ec-error">Error creating event. Please try again.</p>');
        set_transient('ec_submission_message', $message, 60);
        return;
    }

    // error_log('[EC Debug] Event post created with ID ' . $post_id);

    // Handle featured image upload
    if (!empty($_FILES['ec_featured_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $file = $_FILES['ec_featured_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($file['type'], $allowed_types)) {
            $attachment_id = media_handle_upload('ec_featured_image', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
                // error_log('[EC Debug] Featured image uploaded and set, attachment ID ' . $attachment_id);
            }
        }
    }

    // Save meta and taxonomies
    foreach ($fields as $name => $field) {
        if ($field['type'] === 'taxonomy' && !empty($_POST[$name])) {
            wp_set_post_terms($post_id, [(int) $_POST[$name]], $field['taxonomy'], false);
        } elseif (!in_array($name, ['event_title', 'event_description']) && isset($_POST[$name])) {
            update_post_meta($post_id, '_ec_' . $name, sanitize_text_field($_POST[$name]));
        }
    }

    // Success message
    $success_message = apply_filters(
        'ec_message_submission_success',
        '<p class="ec-success">Event submitted successfully! It will be reviewed by an admin.</p>'
    );
    set_transient('ec_submission_message', $success_message, 60);

    // Redirect to same page to prevent resubmission
    wp_safe_redirect( esc_url_raw( $_SERVER['REQUEST_URI'] ) );
    exit;
}
add_action('wp', 'ec_handle_event_submission');
