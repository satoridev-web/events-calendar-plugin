<?php
defined( 'ABSPATH' ) || exit;

// ===================================================================
// Template: archive-event-shortcode.php
// Description: Renders the events archive when displayed via shortcode
// ===================================================================

// Bail if required query variable is not passed
if ( ! isset( $query ) || ! $query instanceof WP_Query ) {
	echo '<p>' . esc_html__( 'Invalid query.', 'events-calendar-plugin' ) . '</p>';
	return;
}

// -------------------------------------------------------------------
// Determine view mode: 'grid' (default) or 'list'
// -------------------------------------------------------------------
$view = isset( $final_view ) ? $final_view : 'grid';
?>

<div class="ec-archive-wrapper" data-default-view="<?php echo esc_attr( $view ); ?>">

	<?php
	// ---------------------------------------------------------------
	// Include filter bar template if available
	// ---------------------------------------------------------------
	$filters_template = ec_locate_template( 'templates/parts/archive-filters.php' );
	if ( $filters_template ) {
		// Hook: before filter bar (extensibility)
		do_action( 'ec_before_archive_filters' );

		include $filters_template;

		// Hook: after filter bar (extensibility)
		do_action( 'ec_after_archive_filters' );
	}

	// ---------------------------------------------------------------
	// Include view toggle template if available
	// ---------------------------------------------------------------
	$toggle_template = ec_locate_template( 'templates/parts/archive-view-toggle.php' );
	if ( $toggle_template ) {
		// Hook: before view toggle
		do_action( 'ec_before_archive_toggle' );

		include $toggle_template;

		// Hook: after view toggle
		do_action( 'ec_after_archive_toggle' );
	}
	?>

	<?php if ( $query->have_posts() ) : ?>
		<div class="<?php echo esc_attr( $view === 'list' ? 'ec-archive-list' : 'ec-archive-grid' ); ?>">

			<?php
			// -----------------------------------------------------------
			// Loop through posts and render event cards or list items
			// -----------------------------------------------------------
			while ( $query->have_posts() ) :
				$query->the_post();

				// Action: before event item
				do_action( 'ec_before_event_item', get_the_ID() );

				// Template part: event-card.php or event-list.php
				get_template_part( 'templates/parts/content', $view === 'list' ? 'event-list' : 'event-card' );

				// Action: after event item
				do_action( 'ec_after_event_item', get_the_ID() );

			endwhile;
			?>

		</div>

		<div class="ec-pagination">
			<?php
			// -----------------------------------------------------------
			// Paginate results
			// -----------------------------------------------------------
			echo paginate_links( [
				'total'   => $query->max_num_pages,
				'current' => max( 1, get_query_var( 'paged' ) ),
			] );
			?>
		</div>

	<?php else : ?>

		<p class="ec-no-events"><?php esc_html_e( 'No events found.', 'events-calendar-plugin' ); ?></p>

	<?php endif; ?>

</div>
