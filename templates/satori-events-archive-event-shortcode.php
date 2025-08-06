<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE: satori-archive-event-shortcode.php
// PURPOSE: Renders events archive for [satori_events_event_archive] shortcode
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// ------------------------------------------------------------------------------
// SANITY CHECK: Ensure $query is a valid WP_Query object
// ------------------------------------------------------------------------------
if (! isset($query) || ! $query instanceof \WP_Query) {
	echo '<p>' . esc_html__('Invalid query.', 'satori-events') . '</p>';
	return;
}

// ------------------------------------------------------------------------------
// SET VIEW: Determine display mode from $view variable ('grid' or 'list')
// ------------------------------------------------------------------------------
$view = (isset($view) && in_array($view, ['grid', 'list'], true)) ? $view : 'grid';
?>

<!-- =============================================================================
     WRAPPER: Archive Output Container
     DATA: data-default-view used by JS to initialize view mode
     ============================================================================= -->
<div class="satori-events-archive-wrapper" data-default-view="<?php echo esc_attr($view); ?>">

	<?php
	// ------------------------------------------------------------------------------
	// INCLUDE: Archive Filters (search + category & location dropdowns)
	// ------------------------------------------------------------------------------
	$filters_template = satori_events_locate_template('parts/satori-archive-filters.php');
	if ($filters_template) {
		do_action('satori_events_before_archive_filters');
		include $filters_template;
		do_action('satori_events_after_archive_filters');
	}

	// ------------------------------------------------------------------------------
	// INCLUDE: View Toggle (grid / list buttons)
	// ------------------------------------------------------------------------------
	$toggle_template = satori_events_locate_template('parts/satori-archive-view-toggle.php');
	if ($toggle_template) {
		do_action('satori_events_before_archive_toggle');
		include $toggle_template;
		do_action('satori_events_after_archive_toggle');
	}
	?>

	<?php if ($query->have_posts()) : ?>

		<!-- ========================================================================
             LAYOUT: Event Items (List or Grid View)
             ARIA: Live region for dynamic updates
             ======================================================================== -->
		<div class="<?php echo esc_attr($view === 'list' ? 'satori-events-archive-list' : 'satori-events-archive-grid'); ?>" role="region" aria-live="polite">

			<?php
			// --------------------------------------------------------------------------
			// LOOP: Render each event using template part
			// --------------------------------------------------------------------------
			while ($query->have_posts()) :
				$query->the_post();

				do_action('satori_events_before_event_item', get_the_ID());

				get_template_part(
					'parts/satori-content-event',
					$view === 'list' ? 'list' : 'card'
				);

				do_action('satori_events_after_event_item', get_the_ID());

			endwhile;

			// Reset post data after custom loop
			wp_reset_postdata();
			?>

		</div>

		<!-- ========================================================================
             PAGINATION: Page Links (if needed)
             ARIA: Landmark role + label for screen readers
             ======================================================================== -->
		<nav class="satori-events-pagination" role="navigation" aria-label="<?php echo esc_attr__('Event pagination', 'satori-events'); ?>">
			<?php
			echo paginate_links([
				'total'     => $query->max_num_pages,
				'current'   => max(1, (int) get_query_var('paged')),
				'prev_text' => esc_html__('&laquo; Prev', 'satori-events'),
				'next_text' => esc_html__('Next &raquo;', 'satori-events'),
				'type'      => 'list',
			]);
			?>
		</nav>

	<?php else : ?>

		<!-- ========================================================================
             EMPTY STATE: No events found
             ======================================================================== -->
		<p class="satori-events-no-events">
			<?php echo esc_html__('No events found.', 'satori-events'); ?>
		</p>

	<?php endif; ?>

</div>