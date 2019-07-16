<?php
/**
 * The template for displaying search results pages.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();

$container = get_theme_mod( 'understrap_container_type' );

?>


<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<?php get_template_part( 'sidebar-templates/sidebar', 'left' ); ?>

			<div class="col-md-10 content-area" id="primary">

				<main class="site-main" id="main" role="main">

				<?php if ( have_posts() ) : ?>
					<div class="card-columns plus" id="gplus">

						<header class="page-header">

								<h1 class="page-title">
									<?php
									printf(
										/* translators: %s: query term */
										esc_html__( 'Search Results for: %s', 'understrap' ),
										'<span>' . get_search_query() . '</span>'
									);
									?>
								</h1>

						</header><!-- .page-header -->

						<?php /* Start the Loop */ ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php
							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'loop-templates/content', 'search' );
							?>

						<?php endwhile; ?>

					<?php else : ?>

						<?php get_template_part( 'loop-templates/content', 'none' ); ?>

					<?php endif; ?>
				</div><!--end card column-->

			</main><!-- #main -->

			<!-- The pagination component -->
			<?php understrap_pagination(); ?>

			</div><!-- #primary -->

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<?php get_footer(); ?>
