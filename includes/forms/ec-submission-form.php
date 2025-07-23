<?php

/**
 * Event Submission Form and Shortcode
 *
 * Provides a frontend event submission form with validation-ready markup.
 *
 * @package Satori_EC
 */

namespace Satori_EC;

defined('ABSPATH') || exit;

/**
 * Render the frontend event submission form.
 *
 * @return string HTML form markup
 */
function ec_render_event_submission_form(): string
{
    $fields = ec_get_event_submission_fields();

    // ------------------------------------------------------------------------------
    // GET: Current query values from URL (if available)
    // ------------------------------------------------------------------------------

    $query_values = [];
    foreach ($fields as $name => $field) {
        $query_key = sanitize_key($name);
        $query_values[$name] = isset($_GET[$query_key]) ? sanitize_text_field($_GET[$query_key]) : '';
    }

    ob_start();

    // ------------------------------------------------------------------------------
    // DISPLAY: Message from previous submission (success or error)
    // ------------------------------------------------------------------------------

    $message = get_transient('ec_submission_message');
    if ($message) {
        echo wp_kses_post($message);
        delete_transient('ec_submission_message');
    }
?>

    <form method="post" enctype="multipart/form-data" class="ec-event-submission-form" novalidate>
        <?php wp_nonce_field('ec_submit_event', 'ec_event_nonce'); ?>

        <?php foreach ($fields as $name => $field) :
            $label         = esc_html($field['label']);
            $is_required   = !empty($field['required']);
            $required_attr = $is_required ? 'required' : '';
            $field_id      = esc_attr($name);
            $prefill       = esc_attr($query_values[$name]);
        ?>
            <p class="ec-field ec-<?php echo $field_id; ?>">
                <label for="<?php echo $field_id; ?>">
                    <?php echo $label; ?>
                    <?php if ($is_required) : ?><span aria-hidden="true">*</span><?php endif; ?>
                </label><br>

                <?php
                switch ($field['type']) {
                    case 'text':
                ?>
                        <input type="text" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo $prefill; ?>" <?php echo $required_attr; ?>>
                    <?php
                        break;

                    case 'textarea':
                    ?>
                        <textarea name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" rows="5" <?php echo $required_attr; ?>><?php echo $prefill; ?></textarea>
                    <?php
                        break;

                    case 'date':
                    ?>
                        <input type="date" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo $prefill; ?>" <?php echo $required_attr; ?>>
                    <?php
                        break;

                    case 'time':
                    ?>
                        <input type="time" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo $prefill; ?>" <?php echo $required_attr; ?>>
                        <?php
                        break;

                    case 'taxonomy':
                        $taxonomy = sanitize_key($field['taxonomy']);
                        $terms = get_terms([
                            'taxonomy'   => $taxonomy,
                            'hide_empty' => false,
                        ]);

                        if (!empty($terms) && !is_wp_error($terms)) : ?>
                            <select name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" <?php echo $required_attr; ?>>
                                <option value=""><?php echo esc_html__('Select...', 'satori-ec'); ?></option>
                                <?php foreach ($terms as $term) : ?>
                                    <option value="<?php echo esc_attr($term->term_id); ?>" <?php selected($prefill, $term->term_id); ?>>
                                        <?php echo esc_html($term->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else : ?>
            <p><em><?php echo esc_html(sprintf(__('No terms found for taxonomy: %s', 'satori-ec'), $taxonomy)); ?></em></p>
<?php
                        endif;
                        break;
                }
?>
</p>
<?php endforeach; ?>

<!-- Honeypot field (hidden to users) -->
<p class="ec-honeypot-field" style="display:none;">
    <label for="ec_event_url"><?php esc_html_e('Leave this field empty', 'satori-ec'); ?></label>
    <input type="text" name="ec_event_url" id="ec_event_url" autocomplete="off" tabindex="-1" aria-hidden="true">
</p>

<!-- Featured image upload -->
<p class="ec-field ec-featured-image">
    <label for="ec_featured_image"><?php esc_html_e('Featured Image', 'satori-ec'); ?></label><br>
    <input type="file" name="ec_featured_image" id="ec_featured_image" accept="image/*">
</p>

<p>
    <button type="submit" name="ec_submit_event" value="1"><?php esc_html_e('Submit Event', 'satori-ec'); ?></button>
</p>
    </form>
<?php

    return ob_get_clean();
}

/**
 * Shortcode to display the event submission form.
 *
 * @return string HTML form markup
 */
function ec_event_submission_shortcode(): string
{
    return ec_render_event_submission_form();
}
add_shortcode('event_submission_form', __NAMESPACE__ . '\\ec_event_submission_shortcode');
