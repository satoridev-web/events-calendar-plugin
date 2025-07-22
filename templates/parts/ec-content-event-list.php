<?php
defined( 'ABSPATH' ) || exit;
?>

<article class="ec-event-list-item">
    <h3 class="ec-event-title"><?php the_title(); ?></h3>
    <div class="ec-event-meta">
        <span class="ec-event-date"><?php echo get_the_date(); ?></span>
        <span class="ec-event-location"><?php the_field( 'event_location' ); ?></span> <!-- ACF field -->
    </div>
    <p class="ec-event-description"><?php the_excerpt(); ?></p>
    <a href="<?php the_permalink(); ?>" class="ec-read-more"><?php esc_html_e( 'Read More', 'events-calendar-plugin' ); ?></a>
</article>
