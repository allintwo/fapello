<?php


// javascript ajax call with click event
add_action('wp_head', 'ajx_action_javascript_wp');
function ajx_action_javascript_wp() {
    ?>

    <script type="text/javascript" >
        jQuery(document).ready(function($) {
           // $('.myajax').click(function(){
                //alert(1);
                var mydata = $(this).data();
                //var termID= $('#locinfo').val();
                $('#wpajaxdisplay').html('<div style="text-align:center;"><img src="<?php echo get_template_directory_uri(); ?>/images/bx_loader.gif" /></div>');
                //console.log(mydata);
                var data = {
                    action: 'fapello_action',
                    //whatever: 1234,
                    id: 123
                };

                $.post('<?php echo esc_url( home_url() ); ?>/wp-admin/admin-ajax.php', data, function(response) {
                    // alert('Got this from the server: ' + response);
                    $('#wpajaxdisplay').html(response);
               // });
            });
        });

    </script>
    <?php
}





add_action('wp_ajax_fapello_action',         'fapello_action_callback_wp');
add_action( 'wp_ajax_nopriv_fapello_action', 'fapello_action_callback_wp' );

function fapello_action_callback_wp() {
    global $wpdb;
    global $userid;

    $pagename = 'none';
    $post_id = 0;
    $model_id =0;
    $media_id= 0;
    $page_number = 1;


    if(isset($_POST['model_id']))
    {
        $model_id = $_POST['model_id'];
    }
    if(isset($_POST['post_id']))
    {
        $post_id = $_POST['post_id'];
    }
    if(isset($_POST['media_id']))
    {
        $media_id = $_POST['media_id'];
    }
    if(isset($_GET['page']))
    {
        $page_number = $_GET['page'];
    }

    $fapello_ajax = new fapello_ajax($wpdb,$userid);
    if(isset($_POST['pagename']))
    {
        $pagename = $_POST['pagename'];
    }

    switch ($pagename){
        case 'media-single':
            $fapello_ajax->a_get_medias_by($model_id);
        break;
        case 'top-followers':
           echo $somemodellist = $fapello_ajax->a_get_model_media_list('top-followers',$page_number);
        break;
        case 'top-likes':
          echo  $somemodellist = $fapello_ajax->a_get_model_media_list('top-likes',$page_number);
        break;
        case 'random':
          echo  $somemodellist = $fapello_ajax->a_get_model_media_list('random',$page_number);
        break;
        case 'trending':
          echo  $somemodellist = $fapello_ajax->a_get_model_media_list('trending',$page_number);
        break;
        case 'videos':
          echo  $somemodellist = $fapello_ajax->a_get_videos_list($page_number);
        break;
        case 'model-list':
          echo   $fapello_ajax->a_get_model_media_list('trending',$page_number);
          // load comments
        break;

        case 'tag-model':
          echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
        break;
        case 'search':
            echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
        break;
        // user pages
        case 'my':
           // echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
            echo $fapello_ajax->a_get_subs_by_userid($page_number,15,1);
        break;

        case 'subs':
            echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
            break;
        case 'my-likes':
            echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
            break;
        case 'login/logout':
            echo   $fapello_ajax->a_get_medias_by($model_id,$page_number);
            break;
        case 'home':
             $fapello_ajax->a_get_homepage_posts($page_number);
        break;
        case 'insert-by-url':
            include_once get_template_directory().'/libs/fakepages/insert-by-url.php';
           //  $fapello_ajax->a_get_homepage_posts($page_number);
        break;

    }

    if($pagename =='media-single')
    {

    }
   // $fapello_ajax->get_homepage_posts();

    exit();
}