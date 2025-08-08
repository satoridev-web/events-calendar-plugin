<?php

// DEBUG MARKER
error_log('[Satori Events] Template Executed: satori-events-archive-event.php');


// Exit if accessed directly
defined('ABSPATH') || exit;

get_header();

global $wp_query;

/* -------------------------------------------------
 * Get current filter / view values from the query
 * ------------------------------------------------- */
$view       = isset($_GET['view']) ? sanitize_key(wp_unslash($_GET['view'])) : 'grid';
$search     = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$sort       = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : '';
$event_type = isset($_GET['event_type']) ? sanitize_key(wp_unslash($_GET['event_type'])) : '';

$base_url   = get_post_type_archive_link('event');
$query_args = wp_unslash($_GET); // preserve query vars in view-toggle buttons
?>

<div class="satori-events-archive-wrapper">
	<h1 class="satori-events-archive-title"><?php post_type_archive_title(); ?></h1>

	<!-- =========================================
	     FILTERS — SEARCH — VIEW TOGGLE (one bar)
	     ========================================= -->
	<div class="satori-events-filters-wrapper">
		<form class="satori-events-filters" method="get" action="<?php echo esc_url($base_url); ?>">
			<!-- Search -->
			<div class="satori-events-filter-search">
				<input type="hidden" name="post_type" value="event" />
				<label for="satori-events-search" class="screen-reader-text">
					<?php esc_html_e('Search events', 'satori-events'); ?>
				</label>
				<input
					type="search"
					id="satori-events-search"
					name="s"
					value="<?php echo esc_attr($search); ?>"
					placeholder="<?php esc_attr_e('Search events…', 'satori-events'); ?>"
					autocomplete="off" />
				<button type="button" class="satori-events-search-clear" aria-label="<?php esc_attr_e('Clear search', 'satori-events'); ?>">&times;</button>
			</div>

			<!-- Sort -->
			<div class="satori-events-filter-group">
				<label for="sort" class="screen-reader-text"><?php esc_html_e('Sort events', 'satori-events'); ?></label>
				<select name="sort" id="sort" onchange="this.form.submit()">
					<option value=""><?php esc_html_e('Sort by…', 'satori-events'); ?></option>
					<option value="date_asc" <?php selected($sort, 'date_asc'); ?>><?php esc_html_e('Upcoming', 'satori-events'); ?></option>
					<option value="date_desc" <?php selected($sort, 'date_desc'); ?>><?php esc_html_e('Past',     'satori-events'); ?></option>
					<option value="title_asc" <?php selected($sort, 'title_asc'); ?>><?php esc_html_e('A–Z',      'satori-events'); ?></option>
					<option value="title_desc" <?php selected($sort, 'title_desc'); ?>><?php esc_html_e('Z–A',      'satori-events'); ?></option>
				</select>
			</div>

			<!-- Category Filter -->
			<div class="satori-events-filter-group">
				<label for="event_type" class="screen-reader-text"><?php esc_html_e('Filter by category', 'satori-events'); ?></label>
				<select name="event_type" id="event_type" onchange="this.form.submit()">
					<option value=""><?php esc_html_e('All categories…', 'satori-events'); ?></option>
					<?php
					$terms = get_terms([
						'taxonomy'   => 'event_type',
						'hide_empty' => false,
					]);
					if (!is_wp_error($terms)) {
						foreach ($terms as $term) {
							printf(
								'<option value="%s" %s>%s</option>',
								esc_attr($term->slug),
								selected($event_type, $term->slug, false),
								esc_html($term->name)
							);
						}
					}
					?>
				</select>
			</div>

			<!-- Preserve current view -->
			<input type="hidden" name="view" value="<?php echo esc_attr($view); ?>" />

			<?php
			// Preserve any other custom query vars except known params
			foreach ($query_args as $key => $val) {
				if (!in_array($key, ['s', 'sort', 'event_type', 'view', 'post_type'], true)) {
					printf(
						'<input type="hidden" name="%s" value="%s" />',
						esc_attr($key),
						esc_attr($val)
					);
				}
			}
			?>
		</form>

		<!-- View Toggle Buttons -->
		<div class="satori-events-view-toggle" role="group" aria-label="<?php esc_attr_e('Toggle between grid and list views', 'satori-events'); ?>">
			<a href="<?php echo esc_url(add_query_arg(array_merge($query_args, ['view' => 'grid']), $base_url)); ?>"
				class="satori-events-toggle-button <?php echo ('grid' === $view) ? 'active' : ''; ?>"
				aria-label="<?php esc_attr_e('Grid view', 'satori-events'); ?>">
				<span class="dashicons dashicons-screenoptions" aria-hidden="true"></span>
				<?php esc_html_e('Grid', 'satori-events'); ?>
			</a>
			<a href="<?php echo esc_url(add_query_arg(array_merge($query_args, ['view' => 'list']), $base_url)); ?>"
				class="satori-events-toggle-button <?php echo ('list' === $view) ? 'active' : ''; ?>"
				aria-label="<?php esc_attr_e('List view', 'satori-events'); ?>">
				<span class="dashicons dashicons-menu-alt" aria-hidden="true"></span>
				<?php esc_html_e('List', 'satori-events'); ?>
			</a>
		</div>
	</div><!-- .satori-events-filters-wrapper -->

	<!-- ========================================
	     Event Output (List vs Grid)
	     ======================================== -->
	<?php if ('list' === $view) : ?>
		<div class="satori-events-archive-list">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="satori-events-list-card">
						<div class="satori-events-list-date">
							<?php
							$event_date = get_post_meta(get_the_ID(), '_satori_events_date', true);
							if ($event_date) {
								echo '<strong>' . esc_html(date_i18n('M j, Y', strtotime($event_date))) . '</strong>';
							}
							?>
						</div>
						<div class="satori-events-list-content">
							<h2 class="satori-events-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="satori-events-list-excerpt"><?php echo esc_html(\Satori_Events\satori_events_get_clean_excerpt(get_the_ID(), 30)); ?></div>
						</div>
					</div>
				<?php endwhile;
			else : ?>
				<p><?php esc_html_e('No events found.', 'satori-events'); ?></p>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<div class="satori-events-archive-grid">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="satori-events-archive-card">
						<?php if (has_post_thumbnail()) : ?>
							<div class="satori-events-archive-image">
								<?php the_post_thumbnail('medium_large'); ?>
							</div>
						<?php endif; ?>

						<?php
						$event_date = get_post_meta(get_the_ID(), '_satori_events_date', true);
						if ($event_date) :
							echo '<div class="satori-events-date-ribbon">' . esc_html(date_i18n('M j, Y', strtotime($event_date))) . '</div>';
						endif;
						?>

						<div class="satori-events-archive-card-content">
							<h2 class="satori-events-archive-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="satori-events-excerpt"><?php echo esc_html(\Satori_Events\satori_events_get_clean_excerpt(get_the_ID(), 30)); ?></div>

							<?php
							$terms = get_the_terms(get_the_ID(), 'event_type');
							if ($terms && ! is_wp_error($terms)) :
								echo '<div class="satori-events-tags">';
								foreach ($terms as $term) {
									echo '<span>' . esc_html($term->name) . '</span>';
								}
								echo '</div>';
							endif;
							?>

							<a href="<?php the_permalink(); ?>" class="satori-events-read-more">
								<?php esc_html_e('Read More →', 'satori-events'); ?>
							</a>
						</div>
					</div>
				<?php endwhile;
			else : ?>
				<p><?php esc_html_e('No events found.', 'satori-events'); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- ========================================
	     Pagination
	     ======================================== -->
	<?php if (paginate_links()) : ?>
		<div class="satori-events-pagination">
			<?php
			echo paginate_links([
				'prev_text' => __('« Prev', 'satori-events'),
				'next_text' => __('Next »', 'satori-events'),
			]);
			?>
		</div>
	<?php endif; ?>
</div><!-- .satori-events-archive-wrapper -->

<!-- Clear-search logic -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const searchInput = document.getElementById('satori-events-search');
		const clearBtn = document.querySelector('.satori-events-search-clear');

		if (!searchInput || !clearBtn) return;

		const toggleBtn = () => {
			clearBtn.style.display = searchInput.value.trim() ? 'block' : 'none';
		};

		toggleBtn();

		searchInput.addEventListener('input', toggleBtn);

		clearBtn.addEventListener('click', () => {
			searchInput.value = '';
			toggleBtn();
			searchInput.form.submit();
		});
	});
</script>

<?php get_footer(); ?>