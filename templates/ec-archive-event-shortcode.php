<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE: ec-archive-event-shortcode.php
// PURPOSE: Renders events archive for [ec_event_archive] shortcode
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// ------------------------------------------------------------------------------
// SANITY CHECK: Ensure $query is a valid WP_Query object
// ------------------------------------------------------------------------------
if (! isset($query) || ! $query instanceof \WP_Query) {
	echo '<p>' . esc_html__('Invalid query.', 'satori-ec') . '</p>';
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
<div class="ec-archive-wrapper" data-default-view="<?php echo esc_attr($view); ?>">

	<?php
	// ------------------------------------------------------------------------------
	// INCLUDE: Archive Filters (search + category dropdown)
	// ------------------------------------------------------------------------------
	$filters_template = ec_locate_template('parts/ec-archive-filters.php');
	if ($filters_template) {
		do_action('ec_before_archive_filters');
		include $filters_template;
		do_action('ec_after_archive_filters');
	}

	// ------------------------------------------------------------------------------
	// INCLUDE: View Toggle (grid / list buttons)
	// ------------------------------------------------------------------------------
	$toggle_template = ec_locate_template('parts/ec-archive-view-toggle.php');
	if ($toggle_template) {
		do_action('ec_before_archive_toggle');
		include $toggle_template;
		do_action('ec_after_archive_toggle');
	}
	?>

	<?php if ($query->have_posts()) : ?>

		<!-- ========================================================================
		     LAYOUT: Event Items (List or Grid View)
		     ARIA: Live region for dynamic updates
		     ======================================================================== -->
		<div class="<?php echo esc_attr($view === 'list' ? 'ec-archive-list' : 'ec-archive-grid'); ?>" role="region" aria-live="polite">

			<?php
			// --------------------------------------------------------------------------
			// LOOP: Render each event using template part
			// --------------------------------------------------------------------------
			while ($query->have_posts()) :
				$query->the_post();

				do_action('ec_before_event_item', get_the_ID());

				get_template_part(
					'parts/ec-content-event',
					$view === 'list' ? 'list' : 'card'
				);

				do_action('ec_after_event_item', get_the_ID());

			endwhile;
			?>

		</div>

		<!-- ========================================================================
		     PAGINATION: Page Links (if needed)
		     ARIA: Landmark role + label for screen readers
		     ======================================================================== -->
		<div class="ec-pagination" role="navigation" aria-label="<?php echo esc_attr__('Event pagination', 'satori-ec'); ?>">
			<?php
			echo paginate_links([
				'total'     => $query->max_num_pages,
				'current'   => max(1, (int) get_query_var('paged')),
				'prev_text' => esc_html__('&laquo; Prev', 'satori-ec'),
				'next_text' => esc_html__('Next &raquo;', 'satori-ec'),
				'type'      => 'list',
			]);
			?>
		</div>

	<?php else : ?>

		<!-- ========================================================================
		     EMPTY STATE: No events found
		     ======================================================================== -->
		<p class="ec-no-events">
			<?php echo esc_html__('No events found.', 'satori-ec'); ?>
		</p>

	<?php endif; ?>

</div>