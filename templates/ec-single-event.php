<?php
/**
 * Template for displaying single Event posts
 * Loaded from plugin templates directory
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $post_id         = get_the_ID();
        $event_date      = get_post_meta($post_id, '_ec_event_date', true);
        $event_time      = get_post_meta($post_id, '_ec_event_time', true);
        $event_location  = get_post_meta($post_id, '_ec_event_location', true);
        $event_types     = get_the_terms($post_id, 'event_type');
        ?>
        
        <article class="ec-event-wrapper">
            <header class="ec-event-header">
                <h1 class="ec-event-title"><?php the_title(); ?></h1>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="ec-event-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
            </header>

            <section class="ec-event-meta">
                <p><strong><?php _e('Date:', 'events-calendar-plugin'); ?></strong>
                    <?php echo $event_date
                        ? esc_html(date_i18n('F j, Y', strtotime($event_date)))
                        : __('TBA', 'events-calendar-plugin'); ?>
                </p>

                <p><strong><?php _e('Time:', 'events-calendar-plugin'); ?></strong>
                    <?php echo $event_time
                        ? esc_html(date_i18n('g:i a', strtotime($event_time)))
                        : __('TBA', 'events-calendar-plugin'); ?>
                </p>

                <p><strong><?php _e('Location:', 'events-calendar-plugin'); ?></strong>
                    <?php echo $event_location
                        ? esc_html($event_location)
                        : __('Not yet specified', 'events-calendar-plugin'); ?>
                </p>

                <?php if (!empty($event_types) && !is_wp_error($event_types)) : ?>
                    <p><strong><?php _e('Type:', 'events-calendar-plugin'); ?></strong>
                        <?php
                        $type_names = wp_list_pluck($event_types, 'name');
                        echo esc_html(implode(', ', $type_names));
                        ?>
                    </p>
                <?php endif; ?>
            </section>

            <section class="ec-event-content">
                <?php the_content(); ?>
            </section>
        </article>

        <?php
    endwhile;
endif;

get_footer();