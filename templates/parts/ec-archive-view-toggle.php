<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE PART: archive-view-toggle.php
// PURPOSE: UI toggle between grid and list views for event archives
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// ------------------------------------------------------------------------------
// GET: Current view from query string (fallback to 'grid')
// ------------------------------------------------------------------------------
$current_view = isset($_GET['view']) ? sanitize_text_field(wp_unslash($_GET['view'])) : 'grid';

// ------------------------------------------------------------------------------
// URL: Build base URL (excluding 'view') to append toggle links
// ------------------------------------------------------------------------------
$current_url = remove_query_arg(['view']);
?>

<!-- ========================================================================== -->
<!-- COMPONENT: View Toggle Buttons                                            -->
<!-- ROLE: ARIA group with labelled toggle links                              -->
<!-- ========================================================================== -->
<div class="ec-view-toggle" role="group" aria-label="<?php echo esc_attr__('Toggle view mode', 'events-calendar-plugin'); ?>">

	<?php
	// --------------------------------------------------------------------------
	// HOOK: Before toggle buttons
	// --------------------------------------------------------------------------
	do_action('ec_before_view_toggle_buttons');
	?>

	<!-- Grid View Button -->
	<a href="<?php echo esc_url(add_query_arg('view', 'grid', $current_url)); ?>"
		class="ec-toggle-btn <?php echo $current_view === 'grid' ? 'is-active' : ''; ?>"
		aria-pressed="<?php echo $current_view === 'grid' ? 'true' : 'false'; ?>">
		<?php echo esc_html__('Grid View', 'events-calendar-plugin'); ?>
	</a>

	<!-- List View Button -->
	<a href="<?php echo esc_url(add_query_arg('view', 'list', $current_url)); ?>"
		class="ec-toggle-btn <?php echo $current_view === 'list' ? 'is-active' : ''; ?>"
		aria-pressed="<?php echo $current_view === 'list' ? 'true' : 'false'; ?>">
		<?php echo esc_html__('List View', 'events-calendar-plugin'); ?>
	</a>

	<?php
	// --------------------------------------------------------------------------
	// HOOK: After toggle buttons
	// --------------------------------------------------------------------------
	do_action('ec_after_view_toggle_buttons');
	?>

</div>