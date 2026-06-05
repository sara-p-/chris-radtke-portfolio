<?php
/**
 * Template Part: Series by Project
 *
 * Loops over every term in the "projects" taxonomy and renders
 * a query loop of associated "series" posts beneath each one.
 *
 * Included via the [series_by_project] shortcode registered
 * in functions.php — do not call directly.
 *
 * Taxonomy slug : projects
 * CPT slug      : series
 */

$project_terms = get_terms( [
	'taxonomy'   => 'projects',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
] );



if ( is_wp_error( $project_terms ) || empty( $project_terms ) ) {
	echo '<p class="no-projects-found">' . esc_html__( 'No projects found.', 'your-theme-textdomain' ) . '</p>';
	return;
}

foreach ( $project_terms as $project ) : ?>

<section class="project-group" id="project-<?php echo esc_attr( $project->slug ); ?>">

  <header class="project-group__header">

    <h2 class="project-group__title">
      <a href="<?php echo esc_url( get_term_link( $project ) ); ?>">
        <?php echo esc_html( $project->name ); ?>
      </a>
    </h2>


  </header>

  <?php if ( ! empty( $project->description ) ) : ?>

  <div class="project-group__description">
    <?php echo wp_kses_post( wpautop( htmlspecialchars_decode( $project->description ) ) ); ?>
  </div>
  <?php endif; ?>
  <?php
		$series_query = new WP_Query( [
			'post_type'      => 'series',
			'tax_query'      => [ [
				'taxonomy' => 'projects',
				'field'    => 'term_id',
				'terms'    => $project->term_id,
			] ],
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		] );

		if ( $series_query->have_posts() ) : ?>

  <ul class="project-group__series-list">

    <?php while ( $series_query->have_posts() ) : $series_query->the_post(); ?>

    <li class="series-item">

      <?php if ( has_post_thumbnail() ) : ?>
      <a href="<?php the_permalink(); ?>" class="series-item__thumbnail-link" tabindex="-1" aria-hidden="true">
        <?php the_post_thumbnail( 'medium', [ 'class' => 'series-item__thumbnail', 'alt' => '' ] ); ?>
      </a>
      <?php endif; ?>

      <div class="series-item__body">

        <h3 class="series-item__title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ( get_the_excerpt() ) : ?>
        <p class="series-item__excerpt"><?php the_excerpt(); ?></p>
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="series-item__read-more">
          <?php esc_html_e( 'View Series', 'your-theme-textdomain' ); ?>
        </a>

      </div>

    </li>

    <?php endwhile; ?>

  </ul>

  <?php else : ?>

  <p class="project-group__empty">
    <?php esc_html_e( 'No series in this project yet.', 'your-theme-textdomain' ); ?>
  </p>

  <?php endif;

		wp_reset_postdata();
		?>

</section>

<?php endforeach;