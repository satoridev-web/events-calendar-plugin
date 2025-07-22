<?php
defined( 'ABSPATH' ) || exit;

// ===================================================================
// Template: archive-view-toggle.php
// Description: UI toggle between grid and list views
// ===================================================================

// Get current view from URL or fallback to 'grid'
$current_view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'grid';

// Prepare current query string for reuse
$current_url = remove_query_arg( [ 'view' ] );
?>

<div class="ec-view-toggle">

	<?php
	// ---------------------------------------------------------------
	// Hook: before toggle links
	// ---------------------------------------------------------------
	do_action( 'ec_before_view_toggle_buttons' );
	?>

	<a href="<?php echo esc_url( add_query_arg( 'view', 'grid', $current_url ) ); ?>"
	   class="ec-toggle-btn <?php echo $current_view === 'grid' ? 'is-active' : ''; ?>">
		<?php esc_html_e( 'Grid View', 'events-calendar-plugin' ); ?>
	</a>

	<a href="<?php echo esc_url( add_query_arg( 'view', 'list', $current_url ) ); ?>"
	   class="ec-toggle-btn <?php echo $current_view === 'list' ? 'is-active' : ''; ?>">
		<?php esc_html_e( 'List View', 'events-calendar-plugin' ); ?>
	</a>

	<?php
	// ---------------------------------------------------------------
	// Hook: after toggle links
	// ---------------------------------------------------------------
	do_action( 'ec_after_view_toggle_buttons' );
	?>

</div>
