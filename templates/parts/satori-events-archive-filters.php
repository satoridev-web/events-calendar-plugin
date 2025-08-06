<?php
defined('ABSPATH') || exit;

// ==============================================================================
// TEMPLATE PART: archive-filters.php
// PURPOSE: Displays search input + category and location filter dropdowns
// AUTHOR: Satori Graphics Pty Ltd
// ==============================================================================

// -----------------------------------------------------------------------------
// GET: Current query values from URL
// -----------------------------------------------------------------------------
$current_search   = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$current_category = isset($_GET['satori_events_category']) ? sanitize_text_field(wp_unslash($_GET['satori_events_category'])) : '';
$current_location = isset($_GET['satori_events_location']) ? sanitize_text_field(wp_unslash($_GET['satori_events_location'])) : '';

// -----------------------------------------------------------------------------
// GET: Event categories and locations with error checking
// -----------------------------------------------------------------------------
$categories = get_terms([
	'taxonomy'   => 'event_category',
	'hide_empty' => false,
]);

$locations = get_terms([
	'taxonomy'   => 'event_location',
	'hide_empty' => false,
]);

if (function_exists('satori_events_log')) {
	satori_events_log('Retrieved ' . count($categories) . ' categories and ' . count($locations) . ' locations for archive filters.');
}
?>

<!-- ========================================================================== -->
<!-- FORM: Event Filters                                                       -->
<!-- ========================================================================== -->
<form
	class="satori-events-filters-form"
	method="get"
	action="<?php echo esc_url(home_url('/')); ?>"
	role="search"
	aria-label="<?php echo esc_attr__('Filter events form', 'satori-events'); ?>">

	<?php do_action('satori_events_before_filters_form_fields'); ?>

	<fieldset class="satori-events-filters-group">
		<legend class="screen-reader-text"><?php echo esc_html__('Filter Events', 'satori-events'); ?></legend>

		<!-- Keyword Search -->
		<div class="satori-events-filter satori-filter-search">
			<label for="satori-events-search"><?php echo esc_html__('Search Events:', 'satori-events'); ?></label>
			<input
				type="search"
				name="s"
				id="satori-events-search"
				value="<?php echo esc_attr($current_search); ?>"
				placeholder="<?php echo esc_attr__('Search…', 'satori-events'); ?>"
				aria-describedby="satori-events-search-desc" />
			<span id="satori-events-search-desc" class="screen-reader-text">
				<?php echo esc_html__('Enter keywords to search events.', 'satori-events'); ?>
			</span>
		</div>

		<!-- Category Filter -->
		<div class="satori-events-filter satori-filter-category">
			<label for="satori-events-category"><?php echo esc_html__('Category:', 'satori-events'); ?></label>
			<select name="satori_events_category" id="satori-events-category" aria-describedby="satori-events-category-desc">
				<option value=""><?php echo esc_html__('All Categories', 'satori-events'); ?></option>
				<?php foreach ($categories as $cat) :
					if (is_array($cat)) $cat = (object) $cat;
					if (!isset($cat->slug, $cat->name)) continue;
				?>
					<option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($current_category, $cat->slug); ?>>
						<?php echo esc_html($cat->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span id="satori-events-category-desc" class="screen-reader-text">
				<?php echo esc_html__('Select event category to filter.', 'satori-events'); ?>
			</span>
		</div>

		<!-- Location Filter -->
		<div class="satori-events-filter satori-filter-location">
			<label for="satori-events-location"><?php echo esc_html__('Location:', 'satori-events'); ?></label>
			<select name="satori_events_location" id="satori-events-location" aria-describedby="satori-events-location-desc">
				<option value=""><?php echo esc_html__('All Locations', 'satori-events'); ?></option>
				<?php foreach ($locations as $loc) :
					if (is_array($loc)) $loc = (object) $loc;
					if (!isset($loc->slug, $loc->name)) continue;
				?>
					<option value="<?php echo esc_attr($loc->slug); ?>" <?php selected($current_location, $loc->slug); ?>>
						<?php echo esc_html($loc->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span id="satori-events-location-desc" class="screen-reader-text">
				<?php echo esc_html__('Select event location to filter.', 'satori-events'); ?>
			</span>
		</div>

		<!-- Submit Button -->
		<div class="satori-events-filter satori-filter-submit">
			<button type="submit" class="satori-events-btn">
				<?php echo esc_html__('Filter', 'satori-events'); ?>
			</button>
		</div>
	</fieldset>

	<?php do_action('satori_events_after_filters_form_fields'); ?>
</form>