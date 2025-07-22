<?php
defined( 'ABSPATH' ) || exit;

// ===================================================================
// Template: archive-filters.php
// Description: Displays search input and category filter dropdown
// ===================================================================

// Get current search and category values from URL
$current_search   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
$current_category = isset( $_GET['ec_category'] ) ? sanitize_text_field( $_GET['ec_category'] ) : '';

// Get event categories
$categories = get_terms( [
	'taxonomy'   => 'event_category',
	'hide_empty' => false,
] );
?>

<form class="ec-filters-form" method="get" action="">

	<?php
	// ---------------------------------------------------------------
	// Hook: before filter inputs
	// ---------------------------------------------------------------
	do_action( 'ec_before_filters_form_fields' );
	?>

	<div class="ec-filter ec-filter-search">
		<label for="ec-search"><?php esc_html_e( 'Search Events:', 'events-calendar-plugin' ); ?></label>
		<input type="search" name="s" id="ec-search" value="<?php echo esc_attr( $current_search ); ?>" placeholder="<?php esc_attr_e( 'Search...', 'events-calendar-plugin' ); ?>" />
	</div>

	<div class="ec-filter ec-filter-category">
		<label for="ec-category"><?php esc_html_e( 'Category:', 'events-calendar-plugin' ); ?></label>
		<select name="ec_category" id="ec-category">
			<option value=""><?php esc_html_e( 'All Categories', 'events-calendar-plugin' ); ?></option>
			<?php foreach ( $categories as $cat ) : ?>
				<option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $current_category, $cat->slug ); ?>>
					<?php echo esc_html( $cat->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="ec-filter ec-filter-submit">
		<button type="submit" class="ec-btn"><?php esc_html_e( 'Filter', 'events-calendar-plugin' ); ?></button>
	</div>

	<?php
	// ---------------------------------------------------------------
	// Hook: after filter inputs
	// ---------------------------------------------------------------
	do_action( 'ec_after_filters_form_fields' );
	?>

</form>
