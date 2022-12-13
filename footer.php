<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>


<div id="showmore" class="showmore" data-page="0" data-max="<?php echo $max_pages;?>">
    <img class="showmore" src="https://fapello.com/assets/images/load.svg" alt="">
</div>
<!--
    <div id="next_page" class="flex justify-center mt-6">
        <a href="https://fapello.com/victoria-justice/page-2/" class="bg-white dark:bg-gray-900 font-semibold my-3 px-6 py-2 rounded-full shadow-md dark:bg-gray-800 dark:text-white">Next Page</a>
    </div>
-->

</div>
</div>

<script>


    var ajax_url = "<?php echo esc_url( home_url() ); ?>/wp-admin/admin-ajax.php";
    var block_show = false;
    function scrollMore(){
        var $target = $('#showmore');

        if (block_show) {
            return false;
        }

        var wt = $(window).scrollTop();
        var wh = $(window).height();
        var et = $target.offset().top;
        var eh = $target.outerHeight();
        var dh = $(document).height();

        if (wt + wh >= et || wh + wt == dh || eh + et < wh)
        {
            var page = $target.attr('data-page');
            page++;
            block_show = true;

            $.ajax({
                method: "POST",
                url:  ajax_url+'?page=' + page,
                dataType: 'html',
                data: ajax_data,
                success: function(data){

                    var div_showmore = $('#showmore').detach();
                    var div_next_page = $('#next_page').detach();

                    $('#content').append(data);
                    block_show = false;

                    if (data.length>50)
                    {
                        div_showmore.appendTo('#content');
                        div_next_page.appendTo('#content');
                    }
                }
            });


            $target.attr('data-page', page);
            if (page ==  $target.attr('data-max')) {
                $target.remove();
                div_showmore.remove();
                div_next_page.remove();
                console.log('max pages visited.. complete');
            }


        }
    }

    $(window).scroll(function() {
        scrollMore();
    });

    $(document).ready(function() {
        scrollMore();
    });


    jQuery(document).on('click','.like_button',function () {


        var like_id = $(this).attr('feed_id');

        $.ajax({
            url: '/ajax/like/feed/' + like_id + '/',
            dataType: 'html',
            success: function(data){
                like_data = JSON.parse(data);


                $( '#like_' + like_id ).html(like_data.like);
                $( '#last_likes_' + like_id ).html(like_data.last_likes);
                $( '#count_likes_' + like_id ).html(like_data.count_likes);

            }
        });
    });


    jQuery(document).on('click','.follow_button',function () {

        var model_id = $(this).attr('model_id');

        $.ajax({
            url: '/ajax/follow/model/' + model_id + '/',
            dataType: 'html',
            success: function(data){
                follow_data = JSON.parse(data);


                $( '#follow_' + model_id ).text(follow_data.text);
            }
        });

    });

</script>

<?php wp_footer(); ?>

</body>

</html>
