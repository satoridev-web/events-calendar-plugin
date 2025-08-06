<?php

/**
 * Template for displaying single Event posts
 * Loaded from plugin templates directory
 */

defined('ABSPATH') || exit;

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id         = get_the_ID();
        $event_date      = get_post_meta($post_id, '_satori_events_date', true);
        $event_time      = get_post_meta($post_id, '_satori_events_time', true);
        $event_location  = get_post_meta($post_id, '_satori_events_location', true);
        $event_types     = get_the_terms($post_id, 'event_type');
?>

        <article class="satori-events-wrapper">
            <header class="satori-events-header">
                <h1 class="satori-events-title"><?php the_title(); ?></h1>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="satori-events-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
            </header>

            <section class="satori-events-meta">
                <p><strong><?php echo esc_html__('Date:', 'events-calendar-plugin'); ?></strong>
                    <?php
                    echo $event_date
                        ? esc_html(date_i18n('F j, Y', strtotime($event_date)))
                        : esc_html__('TBA', 'events-calendar-plugin');
                    ?>
                </p>

                <p><strong><?php echo esc_html__('Time:', 'events-calendar-plugin'); ?></strong>
                    <?php
                    echo $event_time
                        ? esc_html(date_i18n('g:i a', strtotime($event_time)))
                        : esc_html__('TBA', 'events-calendar-plugin');
                    ?>
                </p>

                <p><strong><?php echo esc_html__('Location:', 'events-calendar-plugin'); ?></strong>
                    <?php
                    echo $event_location
                        ? esc_html($event_location)
                        : esc_html__('Not yet specified', 'events-calendar-plugin');
                    ?>
                </p>

                <?php if (!empty($event_types) && !is_wp_error($event_types)) : ?>
                    <p><strong><?php echo esc_html__('Type:', 'events-calendar-plugin'); ?></strong>
                        <?php
                        $type_names = wp_list_pluck($event_types, 'name');
                        echo esc_html(implode(', ', $type_names));
                        ?>
                    </p>
                <?php endif; ?>
            </section>

            <section class="satori-events-content">
                <?php the_content(); ?>
            </section>
        </article>

    <?php
    endwhile;
else :
    ?>
    <p><?php esc_html_e('No event found.', 'events-calendar-plugin'); ?></p>
<?php
endif;

get_footer();
