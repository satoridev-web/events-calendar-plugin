<?php
defined('ABSPATH') || exit;
?>

<!-- ========================================================================== -->
<!-- PARTIAL: Event List Item Template                                          -->
<!-- ========================================================================== -->

<article id="post-<?php the_ID(); ?>" <?php post_class('satori-events-list-item'); ?> role="article" aria-label="<?php the_title_attribute(); ?>">
    <h3 class="satori-events-title">
        <a href="<?php echo esc_url(get_permalink()); ?>">
            <?php the_title(); ?>
        </a>
    </h3>

    <div class="satori-events-meta">
        <span class="satori-events-date" aria-label="<?php echo esc_attr__('Event date', 'satori-events'); ?>">
            <?php echo esc_html(get_the_date()); ?>
        </span>
        <span class="satori-events-location" aria-label="<?php echo esc_attr__('Event location', 'satori-events'); ?>">
            <?php
            // Use ACF get_field() safely; fallback to empty string if not found
            $location = get_field('event_location') ?: '';
            echo esc_html($location);
            ?>
        </span>
    </div>

    <p class="satori-events-description">
        <?php echo wp_kses_post(get_the_excerpt()); ?>
    </p>

    <a href="<?php echo esc_url(get_permalink()); ?>" class="satori-events-read-more" aria-label="<?php printf(esc_attr__('Read more about %s', 'satori-events'), get_the_title_attribute()); ?>">
        <?php esc_html_e('Read More', 'satori-events'); ?>
    </a>

    <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
        <!-- Debug: Event List Item rendered for post ID <?php echo esc_html(get_the_ID()); ?> -->
    <?php endif; ?>
</article>