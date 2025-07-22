<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Render the frontend event submission form.
 */
function ec_render_event_submission_form() {
    $fields = ec_get_event_submission_fields();

    ob_start();

    // Display message if set
    $message = get_transient('ec_submission_message');
    if ($message) {
        echo wp_kses_post($message);
        delete_transient('ec_submission_message');
    }
    ?>
    <form method="post" enctype="multipart/form-data" class="ec-event-submission-form">
        <?php wp_nonce_field('ec_submit_event', 'ec_event_nonce'); ?>

        <?php foreach ($fields as $name => $field) : ?>
            <p class="ec-field ec-<?php echo esc_attr($name); ?>">
                <label for="<?php echo esc_attr($name); ?>">
                    <?php echo esc_html($field['label']); ?>
                    <?php if (!empty($field['required'])) echo ' <span>*</span>'; ?>
                </label><br>

                <?php
                $required = !empty($field['required']) ? 'required' : '';

                switch ($field['type']) {
                    case 'text':
                        echo "<input type='text' name='{$name}' id='{$name}' $required>";
                        break;
                    case 'textarea':
                        echo "<textarea name='{$name}' id='{$name}' rows='5' $required></textarea>";
                        break;
                    case 'date':
                        echo "<input type='date' name='{$name}' id='{$name}' $required>";
                        break;
                    case 'time':
                        echo "<input type='time' name='{$name}' id='{$name}' $required>";
                        break;
                    case 'taxonomy':
                        $taxonomy = $field['taxonomy'];
                        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);

                        if (!empty($terms) && !is_wp_error($terms)) {
                            echo "<select name='{$name}' id='{$name}' $required>";
                            echo "<option value=''>Select...</option>";
                            foreach ($terms as $term) {
                                echo "<option value='" . esc_attr($term->term_id) . "'>" . esc_html($term->name) . "</option>";
                            }
                            echo "</select>";
                        } else {
                            echo '<p><em>No terms found for ' . esc_html($taxonomy) . '.</em></p>';
                        }
                        break;
                }
                ?>
            </p>
        <?php endforeach; ?>

        <!-- Honeypot field -->
        <p class="ec-honeypot-field" style="display:none;">
            <label for="ec_event_url">Leave this field empty</label>
            <input type="text" name="ec_event_url" id="ec_event_url" autocomplete="off">
        </p>

        <!-- Featured image upload -->
        <p class="ec-field ec-featured-image">
            <label for="ec_featured_image">Featured Image</label><br>
            <input type="file" name="ec_featured_image" id="ec_featured_image" accept="image/*">
        </p>

        <p><button type="submit" name="ec_submit_event" value="1">Submit Event</button></p>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode to display the form.
 */
function ec_event_submission_shortcode() {
    return ec_render_event_submission_form();
}
add_shortcode('event_submission_form', 'ec_event_submission_shortcode');
