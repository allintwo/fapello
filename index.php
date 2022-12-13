<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Understrap
 */
ini_set('error_reporting',1);
ini_set('display_errors',1);


// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );

$theme_dir = get_template_directory();
$the_theme         = wp_get_theme();
$theme_version     = $the_theme->get( 'Version' );

$HTTP = site_url();
$THTTP = get_template_directory_uri();

?>

<?php if ( is_front_page() && is_home() ) : ?>
	<?php get_template_part( 'global-templates/hero' ); ?>
<?php endif; ?>


    <div class="container m-auto">

        <div class="max-w-2xl mx-auto">
            <div class="justify-center lg:space-x-10 lg:space-y-0 space-y-5">
    <!-- left sidebar-->
                <div id="content" class="space-y-5">
			<!-- Do the left sidebar check and opens the primary div -->


			<main class="site-main" id="main">

				<?php
				if ( have_posts() ) {
					// Start the Loop.
					while ( have_posts() ) {
						the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'loop-templates/content', get_post_format() );
					}
				}
				?>
			</main><!-- #main -->
                </div>
            </div>
        </div>
</div>

<?php
get_footer();
