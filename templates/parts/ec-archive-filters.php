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
$current_category = isset($_GET['ec_category']) ? sanitize_text_field(wp_unslash($_GET['ec_category'])) : '';
$current_location = isset($_GET['ec_location']) ? sanitize_text_field(wp_unslash($_GET['ec_location'])) : '';

// -----------------------------------------------------------------------------
// GET: Event categories and locations
// -----------------------------------------------------------------------------
$categories = get_terms([
	'taxonomy'   => 'event_category',
	'hide_empty' => false,
]);

$locations = get_terms([
	'taxonomy'   => 'event_location',
	'hide_empty' => false,
]);
?>

<!-- ========================================================================== -->
<!-- FORM: Event Filters                                                       -->
<!-- ========================================================================== -->
<form
	class="ec-filters-form"
	method="get"
	action="<?php echo esc_url(home_url('/')); ?>"
	role="search"
	aria-label="<?php echo esc_attr__('Filter events form', 'satori-ec'); ?>">

	<?php do_action('ec_before_filters_form_fields'); ?>

	<fieldset class="ec-filters-group">
		<legend class="screen-reader-text"><?php echo esc_html__('Filter Events', 'satori-ec'); ?></legend>

		<!-- Keyword Search -->
		<div class="ec-filter ec-filter-search">
			<label for="ec-search"><?php echo esc_html__('Search Events:', 'satori-ec'); ?></label>
			<input
				type="search"
				name="s"
				id="ec-search"
				value="<?php echo esc_attr($current_search); ?>"
				placeholder="<?php echo esc_attr__('Search…', 'satori-ec'); ?>"
				aria-describedby="ec-search-desc" />
			<span id="ec-search-desc" class="screen-reader-text">
				<?php echo esc_html__('Enter keywords to search events.', 'satori-ec'); ?>
			</span>
		</div>

		<!-- Category Filter -->
		<div class="ec-filter ec-filter-category">
			<label for="ec-category"><?php echo esc_html__('Category:', 'satori-ec'); ?></label>
			<select name="ec_category" id="ec-category" aria-describedby="ec-category-desc">
				<option value=""><?php echo esc_html__('All Categories', 'satori-ec'); ?></option>
				<?php foreach ($categories as $cat) :
					if (is_array($cat)) $cat = (object) $cat;
					if (!isset($cat->slug, $cat->name)) continue;
				?>
					<option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($current_category, $cat->slug); ?>>
						<?php echo esc_html($cat->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span id="ec-category-desc" class="screen-reader-text">
				<?php echo esc_html__('Select event category to filter.', 'satori-ec'); ?>
			</span>
		</div>

		<!-- Location Filter -->
		<div class="ec-filter ec-filter-location">
			<label for="ec-location"><?php echo esc_html__('Location:', 'satori-ec'); ?></label>
			<select name="ec_location" id="ec-location" aria-describedby="ec-location-desc">
				<option value=""><?php echo esc_html__('All Locations', 'satori-ec'); ?></option>
				<?php foreach ($locations as $loc) :
					if (is_array($loc)) $loc = (object) $loc;
					if (!isset($loc->slug, $loc->name)) continue;
				?>
					<option value="<?php echo esc_attr($loc->slug); ?>" <?php selected($current_location, $loc->slug); ?>>
						<?php echo esc_html($loc->name); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span id="ec-location-desc" class="screen-reader-text">
				<?php echo esc_html__('Select event location to filter.', 'satori-ec'); ?>
			</span>
		</div>

		<!-- Submit Button -->
		<div class="ec-filter ec-filter-submit">
			<button type="submit" class="ec-btn">
				<?php echo esc_html__('Filter', 'satori-ec'); ?>
			</button>
		</div>
	</fieldset>

	<?php do_action('ec_after_filters_form_fields'); ?>
</form>