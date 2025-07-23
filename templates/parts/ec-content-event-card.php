<?php
defined('ABSPATH') || exit;
?>

<!-- ========================================================================== -->
<!-- PARTIAL: Event Card Template                                               -->
<!-- ========================================================================== -->

<article id="post-<?php the_ID(); ?>" <?php post_class('ec-event-card'); ?> role="article" aria-label="<?php the_title_attribute(); ?>">
    <h3 class="ec-event-title">
        <a href="<?php echo esc_url(get_permalink()); ?>">
            <?php the_title(); ?>
        </a>
    </h3>

    <div class="ec-event-date" aria-label="<?php esc_attr_e('Event date', 'satori-ec'); ?>">
        <?php echo esc_html(get_the_date()); ?>
    </div>

    <div class="ec-event-excerpt">
        <?php echo wp_kses_post(get_the_excerpt()); ?>
    </div>

    <a href="<?php echo esc_url(get_permalink()); ?>" class="ec-read-more" aria-label="<?php printf(esc_attr__('Read more about %s', 'satori-ec'), get_the_title()); ?>">
        <?php esc_html_e('Read More', 'satori-ec'); ?>
    </a>
</article>