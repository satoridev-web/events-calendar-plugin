<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE: archive-event-shortcode.php
// PURPOSE: Renders the events archive when displayed via the [ec_event_archive] shortcode
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// ----------------------------------------------------------------------------
// SANITY CHECK: Ensure the $query variable is a valid WP_Query object
// ----------------------------------------------------------------------------
if (! isset($query) || ! $query instanceof WP_Query) {
	echo '<p>' . esc_html__('Invalid query.', 'events-calendar-plugin') . '</p>';
	return;
}

// ----------------------------------------------------------------------------
// SET VIEW MODE: Determine whether to display events in 'grid' or 'list' format
// ----------------------------------------------------------------------------
$view = isset($final_view) ? $final_view : 'grid';
?>

<div class="ec-archive-wrapper" data-default-view="<?php echo esc_attr($view); ?>">

	<?php
	// ----------------------------------------------------------------------------
	// INCLUDE: Archive Filters Bar
	// ----------------------------------------------------------------------------
	$filters_template = ec_locate_template('templates/parts/archive-filters.php');
	if ($filters_template) {
		do_action('ec_before_archive_filters');
		include $filters_template;
		do_action('ec_after_archive_filters');
	}

	// ----------------------------------------------------------------------------
	// INCLUDE: View Toggle Buttons (Grid/List)
	// ----------------------------------------------------------------------------
	$toggle_template = ec_locate_template('templates/parts/archive-view-toggle.php');
	if ($toggle_template) {
		do_action('ec_before_archive_toggle');
		include $toggle_template;
		do_action('ec_after_archive_toggle');
	}
	?>

	<?php if ($query->have_posts()) : ?>
		<div
			class="<?php echo esc_attr($view === 'list' ? 'ec-archive-list' : 'ec-archive-grid'); ?>"
			role="region"
			aria-live="polite">
			<?php
			// ----------------------------------------------------------------------------
			// LOOP: Render each event using relevant partial (grid or list)
			// ----------------------------------------------------------------------------
			while ($query->have_posts()) :
				$query->the_post();

				do_action('ec_before_event_item', get_the_ID());

				get_template_part(
					'templates/parts/content',
					$view === 'list' ? 'event-list' : 'event-card'
				);

				do_action('ec_after_event_item', get_the_ID());

			endwhile;
			?>
		</div>

		<div class="ec-pagination">
			<?php
			// ----------------------------------------------------------------------------
			// PAGINATION: Display pagination links for archive results
			// ----------------------------------------------------------------------------
			echo paginate_links([
				'total'   => $query->max_num_pages,
				'current' => max(1, get_query_var('paged')),
			]);
			?>
		</div>

	<?php else : ?>

		<p class="ec-no-events">
			<?php esc_html_e('No events found.', 'events-calendar-plugin'); ?>
		</p>

	<?php endif; ?>

</div>