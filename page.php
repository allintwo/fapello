<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );

global $vpage;

?>
    <div class="container pro-container m-auto">

        <?php
        if(is_single())
        {
          //  echo 'single page';
        }else{
          //  echo 'have posts';
            $post_id = get_the_ID();
            if($post_id < 1)
            {
               // echo $post_id;
              ///  echo 'loading- libs/single-media.php';
                $fakepage_urls = [
                    'my',
                    'subs',
                    'my-likes',
                    'test',
                    'discussion',
                    'insert-by-url',
                    'search',
                    'cronjobs',
                    'comments',
                    'contacts',
                ];


                if(($vpage =='trending')||($vpage =='random')||($vpage =='videos')||($vpage =='top-likes')||($vpage =='top-followers'))
                {
                    get_template_part( 'libs/pages/model-list', '' );
                }elseif($vpage =='media-single')
                {
                    get_template_part( 'libs/pages/single-media', '' );
                }elseif (in_array($vpage,$fakepage_urls))
                {
                    get_template_part( 'libs/pages/fakepage', '' );
                }

            }else{
                while ( have_posts() ) {
                    echo 'loading- content-single.php';
                    the_post();
                    get_template_part( 'loop-templates/content', 'single' );
                }
            }
        }


        ?>
    </div><!-- #single-wrapper -->
<?php
get_footer();
