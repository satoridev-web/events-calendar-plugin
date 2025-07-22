<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE PART: archive-filters.php
// PURPOSE: Displays search input and category filter dropdown on event archive
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// ------------------------------------------------------------------------------
// GET: Current query values from URL (if available)
// ------------------------------------------------------------------------------
$current_search   = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$current_category = isset($_GET['ec_category']) ? sanitize_text_field($_GET['ec_category']) : '';

// ------------------------------------------------------------------------------
// GET: Event categories (for filter dropdown)
// ------------------------------------------------------------------------------
$categories = get_terms([
	'taxonomy'   => 'event_category',
	'hide_empty' => false,
]);
?>

<!-- ==========================================================
     FORM: Event Filters
     ARIA: Descriptive labels + grouping for screen readers
     ========================================================== -->
<form class="ec-filters-form" method="get" action="" role="search" aria-label="<?php esc_attr_e('Filter events form', 'events-calendar-plugin'); ?>">

	<?php
	// --------------------------------------------------------------------------
	// HOOK: Before filter fields
	// --------------------------------------------------------------------------
	do_action('ec_before_filters_form_fields');
	?>

	<fieldset class="ec-filters-group">
		<legend class="screen-reader-text"><?php esc_html_e('Filter Events', 'events-calendar-plugin'); ?></legend>

		<!-- Keyword Search -->
		<div class="ec-filter ec-filter-search">
			<label for="ec-search"><?php esc_html_e('Search Events:', 'events-calendar-plugin'); ?></label>
			<input
				type="search"
				name="s"
				id="ec-search"
				value="<?php echo esc_attr($current_search); ?>"
				placeholder="<?php esc_attr_e('Search…', 'events-calendar-plugin'); ?>" />
		</div>

		<!-- Category Filter -->
		<div class="ec-filter ec-filter-category">
			<label for="ec-category"><?php esc_html_e('Category:', 'events-calendar-plugin'); ?></label>
			<select name="ec_category" id="ec-category">
				<option value=""><?php esc_html_e('All Categories', 'events-calendar-plugin'); ?></option>
				<?php foreach ($categories as $cat) : ?>
					<option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($current_category, $cat->slug); ?>>
						<?php echo esc_html($cat->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- Submit Button -->
		<div class="ec-filter ec-filter-submit">
			<button type="submit" class="ec-btn">
				<?php esc_html_e('Filter', 'events-calendar-plugin'); ?>
			</button>
		</div>

	</fieldset>

	<?php
	// --------------------------------------------------------------------------
	// HOOK: After filter fields
	// --------------------------------------------------------------------------
	do_action('ec_after_filters_form_fields');
	?>

</form>