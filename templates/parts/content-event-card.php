<?php
defined( 'ABSPATH' ) || exit;
?>

<article class="ec-event-card">
    <h3 class="ec-event-title"><?php the_title(); ?></h3>
    <div class="ec-event-date"><?php echo get_the_date(); ?></div>
    <div class="ec-event-excerpt"><?php the_excerpt(); ?></div>
    <a href="<?php the_permalink(); ?>" class="ec-read-more"><?php esc_html_e( 'Read More', 'events-calendar-plugin' ); ?></a>
</article>
