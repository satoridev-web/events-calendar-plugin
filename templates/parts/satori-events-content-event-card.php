<?php
defined('ABSPATH') || exit;
?>

<!-- ========================================================================== -->
<!-- PARTIAL: Event Card Template                                               -->
<!-- ========================================================================== -->

<article id="post-<?php the_ID(); ?>" <?php post_class('satori-events-card'); ?> role="article" aria-label="<?php the_title_attribute(); ?>">
    <h3 class="satori-events-title">
        <a href="<?php echo esc_url(get_permalink()); ?>">
            <?php the_title(); ?>
        </a>
    </h3>

    <div class="satori-events-date" aria-label="<?php echo esc_attr__('Event date', 'satori-events'); ?>">
        <?php echo esc_html(get_the_date()); ?>
    </div>

    <div class="satori-events-excerpt">
        <?php echo wp_kses_post(get_the_excerpt()); ?>
    </div>

    <a href="<?php echo esc_url(get_permalink()); ?>" class="satori-events-read-more" aria-label="<?php printf(esc_attr__('Read more about %s', 'satori-events'), get_the_title_attribute()); ?>">
        <?php esc_html_e('Read More', 'satori-events'); ?>
    </a>

    <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
        <!-- Debug: Event Card rendered for post ID <?php echo esc_html(get_the_ID()); ?> -->
    <?php endif; ?>
</article>