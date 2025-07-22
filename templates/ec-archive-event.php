<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

get_header();

/* -------------------------------------------------
 * Get current filter / view values from the query
 * -------------------------------------------------*/
$view        = isset( $_GET['view'] )        ? sanitize_key( $_GET['view'] )  : 'grid';
$search      = isset( $_GET['s'] )           ? sanitize_text_field( $_GET['s'] ) : '';
$sort        = isset( $_GET['sort'] )        ? sanitize_key( $_GET['sort'] )  : '';
$event_type  = isset( $_GET['event_type'] )  ? sanitize_key( $_GET['event_type'] ) : '';

$base_url    = get_post_type_archive_link( 'event' );
$query_args  = $_GET; // used for view‑toggle links
?>

<div class="ec-archive-wrapper">
	<h1 class="ec-archive-title"><?php post_type_archive_title(); ?></h1>

	<!-- =========================================
	     FILTERS ‑ SEARCH ‑ VIEW TOGGLE (one bar)
	     ========================================= -->
	<div class="ec-event-filters-wrapper">

		<form class="ec-event-filters" method="get" action="<?php echo esc_url( $base_url ); ?>">
			<!-- ----- Search ----------------------- -->
      <div class="ec-filter-search">
        <input type="hidden" name="post_type" value="event" />
        <label for="ec-search" class="screen-reader-text"><?php esc_html_e('Search events', 'events-calendar-plugin'); ?></label>
        <input
          type="search"
          id="ec-search"
          name="s"
          value="<?php echo esc_attr($search); ?>"
          placeholder="<?php esc_attr_e('Search events…', 'events-calendar-plugin'); ?>"
          autocomplete="off"
        />
        <button type="button" class="ec-search-clear" aria-label="<?php esc_attr_e('Clear search', 'events-calendar-plugin'); ?>">&times;</button>
      </div>

			<!-- ----- Sort ------------------------- -->
			<div class="ec-filter-group">
				<label for="sort" class="screen-reader-text"><?php esc_html_e( 'Sort events', 'events-calendar-plugin' ); ?></label>
				<select name="sort" id="sort" onchange="this.form.submit()">
					<option value=""><?php esc_html_e( 'Sort by…', 'events-calendar-plugin' ); ?></option>
					<option value="date_asc"   <?php selected( $sort, 'date_asc'   ); ?>><?php esc_html_e( 'Upcoming', 'events-calendar-plugin' ); ?></option>
					<option value="date_desc"  <?php selected( $sort, 'date_desc'  ); ?>><?php esc_html_e( 'Past',     'events-calendar-plugin' ); ?></option>
					<option value="title_asc"  <?php selected( $sort, 'title_asc'  ); ?>><?php esc_html_e( 'A‑Z',      'events-calendar-plugin' ); ?></option>
					<option value="title_desc" <?php selected( $sort, 'title_desc' ); ?>><?php esc_html_e( 'Z‑A',      'events-calendar-plugin' ); ?></option>
				</select>
			</div>

			<!-- ----- Category filter -------------- -->
			<div class="ec-filter-group">
				<label for="event_type" class="screen-reader-text"><?php esc_html_e( 'Filter by category', 'events-calendar-plugin' ); ?></label>
				<select name="event_type" id="event_type" onchange="this.form.submit()">
					<option value=""><?php esc_html_e( 'All categories…', 'events-calendar-plugin' ); ?></option>
					<?php
					$terms = get_terms(
						array(
							'taxonomy'   => 'event_type',
							'hide_empty' => false,
						)
					);
					foreach ( $terms as $term ) {
						printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $term->slug ),
							selected( $event_type, $term->slug, false ),
							esc_html( $term->name )
						);
					}
					?>
				</select>
			</div>

			<!-- ----- preserve current view -------- -->
			<input type="hidden" name="view" value="<?php echo esc_attr( $view ); ?>" />

			<?php
			// Preserve *any* other query‑vars so users don’t lose them.
			foreach ( $_GET as $key => $val ) {
				if ( ! in_array( $key, array( 's', 'sort', 'event_type', 'view' ), true ) ) {
					printf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $key ), esc_attr( wp_unslash( $val ) ) );
				}
			}
			?>
		</form>

		<!-- ----- View toggle buttons ------------ -->
		<div class="ec-view-toggle">
			<a href="<?php echo esc_url( add_query_arg( array_merge( $query_args, array( 'view' => 'grid' ) ), $base_url ) ); ?>"
			   class="ec-toggle-button <?php echo ( 'grid' === $view ) ? 'active' : ''; ?>"
			   aria-label="<?php esc_attr_e( 'Grid view', 'events-calendar-plugin' ); ?>">
				<span class="dashicons dashicons-screenoptions" aria-hidden="true"></span>
				<?php esc_html_e( 'Grid', 'events-calendar-plugin' ); ?>
			</a>

			<a href="<?php echo esc_url( add_query_arg( array_merge( $query_args, array( 'view' => 'list' ) ), $base_url ) ); ?>"
			   class="ec-toggle-button <?php echo ( 'list' === $view ) ? 'active' : ''; ?>"
			   aria-label="<?php esc_attr_e( 'List view', 'events-calendar-plugin' ); ?>">
				<span class="dashicons dashicons-menu-alt" aria-hidden="true"></span>
				<?php esc_html_e( 'List', 'events-calendar-plugin' ); ?>
			</a>
		</div>
	</div><!-- .ec-event-filters-wrapper -->

	<!-- ========================================
	     Event Output (List vs Grid)
	     ======================================== -->
	<?php if ( 'list' === $view ) : ?>

		<div class="ec-archive-list">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="ec-list-card">
					<div class="ec-list-date">
						<?php
						$event_date = get_post_meta( get_the_ID(), '_ec_event_date', true );
						if ( $event_date ) {
							echo '<strong>' . esc_html( date_i18n( 'M j, Y', strtotime( $event_date ) ) ) . '</strong>';
						}
						?>
					</div>
					<div class="ec-list-content">
						<h2 class="ec-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="ec-list-excerpt"><?php echo esc_html( ec_get_clean_excerpt( get_the_ID(), 30 ) ); ?></div>
					</div>
				</div>
			<?php endwhile; else : ?>
				<p><?php esc_html_e( 'No events found.', 'events-calendar-plugin' ); ?></p>
			<?php endif; ?>
		</div><!-- .ec-archive-list -->

	<?php else : ?>

		<div class="ec-archive-grid">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<div class="ec-archive-card">
					<?php
					if ( has_post_thumbnail() ) {
						echo '<div class="ec-archive-image">';
						the_post_thumbnail( 'medium_large' );
						echo '</div>';
					}

					$event_date = get_post_meta( get_the_ID(), '_ec_event_date', true );
					if ( $event_date ) {
						echo '<div class="ec-event-date-ribbon">' . esc_html( date_i18n( 'M j, Y', strtotime( $event_date ) ) ) . '</div>';
					}
					?>
					<div class="ec-archive-card-content">
						<h2 class="ec-archive-card-title"><?php the_title(); ?></h2>
						<div class="ec-event-excerpt"><?php echo esc_html( ec_get_clean_excerpt( get_the_ID(), 30 ) ); ?></div>

						<?php
						$terms = get_the_terms( get_the_ID(), 'event_type' );
						if ( $terms && ! is_wp_error( $terms ) ) :
							echo '<div class="ec-event-tags">';
							foreach ( $terms as $term ) {
								echo '<span>' . esc_html( $term->name ) . '</span>';
							}
							echo '</div>';
						endif;
						?>
						<a href="<?php the_permalink(); ?>" class="ec-event-read-more"><?php esc_html_e( 'Read More →', 'events-calendar-plugin' ); ?></a>
					</div><!-- .content -->
				</div><!-- .card -->
			<?php endwhile; else : ?>
				<p><?php esc_html_e( 'No events found.', 'events-calendar-plugin' ); ?></p>
			<?php endif; ?>
		</div><!-- .ec-archive-grid -->

	<?php endif; ?>

	<!-- ========================================
	     Pagination
	     ======================================== -->
	<?php
	if ( paginate_links() ) :
		?>
		<div class="ec-pagination">
			<?php
			echo paginate_links(
				array(
					'prev_text' => __( '« Prev', 'events-calendar-plugin' ),
					'next_text' => __( 'Next »', 'events-calendar-plugin' ),
				)
			);
			?>
		</div>
	<?php endif; ?>

</div><!-- .ec-archive-wrapper -->

<!-- Clear‑search logic -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('ec-search');
  const clearBtn    = document.querySelector('.ec-search-clear');

  if (!searchInput || !clearBtn) return;

  // Helper: toggle button visibility
  const toggleBtn = () => {
    clearBtn.style.display = searchInput.value.trim() ? 'block' : 'none';
  };

  // Initial state
  toggleBtn();

  // Show/Hide on user typing
  searchInput.addEventListener('input', toggleBtn);

  // Clear + resubmit on click
  clearBtn.addEventListener('click', () => {
    searchInput.value = '';
    toggleBtn();
    searchInput.form.submit();
  });
});
</script>

<?php get_footer(); ?>
