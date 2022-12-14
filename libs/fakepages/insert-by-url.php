<?php
set_time_limit(0);
ini_set('mbstring.func_overload 0',0);
ini_set('max_execution_time',0);
ini_set('memory_limit','2048M'); // 2GB

global $fapello_ajax;

if ( ! function_exists( 'post_exists' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/post.php' );
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

}




include_once get_template_directory() . '/libs/simple_html_dom.php';
include_once get_template_directory() .'/libs/scraper/common_func.php';
include_once get_template_directory() .'/libs/scraper/fapello.com.php';




//$links = $cc->get_all_image_url('helly-von-valentine');
//print_r($links);
//$cc->GetPageData('https://fapello.com/paige-vanzant/');
//echo $cc->get_json();

$response = [
    'message' => '',
    'status'=> 404,

];
$target_file = 'urllist.txt';
if(isset($_FILES['urlllistxt']))
{

    $uploaded_file = $_FILES["urlllistxt"]["tmp_name"];
    move_uploaded_file($_FILES["urlllistxt"]["tmp_name"], $target_file);
}

function r_write_update_line($newarray)
{

    file_put_contents('urllist.txt', implode(PHP_EOL, $newarray));
}

/*
if(isset($_REQUEST['cron_text_upload']))
{
    ini_set('default_charset', 'UTF-8');
    ini_set('max_execution_time',180); // 5 minute
    mb_internal_encoding("UTF-8");

    echo "<div><b>Recent Update List</b><a href='/urllist.txt'>Click here</a></div>";
    if(is_file($target_file))
    {

        $filestr = file_get_contents($target_file);
        $lines = preg_split("/\r\n|\n|\r/",$filestr);
        shuffle($lines);
      //  $lines =  array_reverse($lines);
        $savable_array = $lines;
        $i = 0;
                // process the line read.
        foreach ($lines as $line)
        {
            flush();
            if(strpos($line,'--done') >2)
            {
                echo "<li style='background-color: #67f726'>[$i] {$line}</li>";
            }else{
                //do work and update
                $url = trim($line);
                $ptrn = '#.*fapello.com/[^/]+/$#';
                if(preg_match($ptrn,$url,$mtc))
                {
                    $savable_array[$i] = $url .'--done '.date("Y-m-d h:i:s");
                    r_write_update_line($savable_array);
                   // echo $line;exit();
                  if(  fapello_get_urls_log($url))
                  {

                  }else{
                      built_in_fapello_scraper($url);
                  }




                    echo "<li style='background-color: lightgreen'>[$i] {$line} --Added</li>";
                }else{
                    echo "<li style='background-color: lightpink'>[$i] {$line} --Wrong url</li>";
                }
               // echo $line;
            }
            $i++;
            flush();
        } // lines

    }

    echo "<li style='background-color: red'>---ALL Complete --- </li>";

}
*/

if(isset($_REQUEST['url']))
{
   // add_new_post();
    $url = $_REQUEST['url'];

    if(strpos($url,'fapello.com/')>0)
    {
        $response['status'] = 199;
        header("Content-Type: application/json");

        if( fapello_get_urls_log($url))
        {
            $response['status'] = 203;

        }else{
            $response['status'] = 200;
            fapello_add_urls_log($url);//
         $scraper_data =  built_in_fapello_scraper($url);
        }

    }else{
        $response['status'] = 501;
    }


}else{

    $response['status'] = 404;

    echo "<h3>When you visit this page... automaticaly scrape new content from fapello</h3>";
    echo <<<adkgsadsafgdusafdsaudfasduisa
<div>
<div>
<h3>Upload file</h3>
<span><a href="?cron_text_upload=1">Check Status</a></span>
</div>
<label>UPload by .txt file</label>
    <form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="urlllistxt">
    <input type="submit" value="upload">
    </form>
</div>
adkgsadsafgdusafdsaudfasduisa;


   // echo 'no page inserted';
    /*
    $cc = new common_fapello_xworker();
    $pages = $cc->GetPageList();
    foreach ($pages as $key => $val)
    {
        built_in_fapello_scraper($val);
    }
   // */
}


// sleep(30);

echo json_encode($response);


function built_in_fapello_scraper($url)
{
    $cc = new common_fapello_xworker();
    $slug = basename($url);
    $upload_dir   = wp_upload_dir();
    $mod_dir = $upload_dir['basedir'].'/models/';

    if(is_dir($mod_dir))
    {

    }else{
        mkdir($mod_dir,0777,1);
    }
// index system added
    $index_str = $slug[0];
    $u_basedir = $upload_dir['basedir'].'/models/'.$index_str .'/'.$slug .'/';
    $u_baseurl = $upload_dir['baseurl'].'/models/'.$index_str .'/'.$slug .'/';

     // $cc->GetPageData($url);
    $cc->api_get_cache_data($url);

    $data =$cc->build_wp_post_output($u_baseurl,$u_basedir);

    $title = $data['title'];
    $slug = $data['slug'];
    $image = $data['image'];
    $content = $data['content'];
    $tag_desc = $data['tag_desc'];
    $dbgifo = fapello_add_new_post($title,$image,$slug,$content,$tag_desc);
    $data['posting_info'] = $dbgifo;
    return $data;
}



function fapello_add_new_post($title,$image,$slug,$content,$tag_desc)
{
    $post = array(
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'post',
        'post_name' => $slug,
        'tax_input'    => array(
            'Model' => array($slug)
        )
    );

    //  wp_set_post_terms( $post_id, 'mota-bhai', 'Model' );

    // $post_id = post_exists( $title );
    $post_id = the_slug_exists($slug);

    if (!$post_id) {
        // code here
        @kses_remove_filters();
        $post_id = wp_insert_post($post);
        @kses_init_filters();

    }else{
        $my_post = array(
            'ID'           => $post_id,
            //'post_title'   => 'This is the post title.',
            'post_content' => $content
        );
        wp_update_post( $my_post );
    }

    // update model taxonomy description
    // get term id by slug
    global $fapello_ajax;

    $term_obj_list = get_the_terms( $post_id, 'Model' );

    if (is_array($term_obj_list)){
        foreach ($term_obj_list as $term)
        {
            $update = wp_update_term( $term->term_id, 'Model', array(
                //'name' => 'Non Catégorisé',
                // 'slug' => 'non-categorise',
                'description' => $tag_desc
            ));
            break;
        }
    }


    // set f image
    if($post_id)
    {
        $featured_img_url = get_the_post_thumbnail_url($post_id);
        if($featured_img_url)
        {

        }else{
            // insert new try

            if(strlen($image)>10)
            {
// magic sideload image returns an HTML image, not an ID
                $media = media_sideload_image($image, $post_id);
// therefore we must find it so we can set it as featured ID
                if(!empty($media) && !is_wp_error($media)){
                    $args = array(
                        'post_type' => 'attachment',
                        'posts_per_page' => -1,
                        'post_status' => 'any',
                        'post_parent' => $post_id
                    );

                    // reference new image to set as featured
                    $attachments = get_posts($args);

                    if(isset($attachments) && is_array($attachments)){
                        foreach($attachments as $attachment){
                            // grab source of full size images (so no 300x150 nonsense in path)
                            $image = wp_get_attachment_image_src($attachment->ID, 'full');
                            // determine if in the $media image we created, the string of the URL exists
                            if(strpos($media, $image[0]) !== false){
                                // if so, we found our image. set it as thumbnail
                                set_post_thumbnail($post_id, $attachment->ID);
                                // only want one image
                                break;
                            }
                        }
                    }
                }

            }

        }
    }


}

function fapello_add_urls_log($url)
{
    global $wpdb;
   // $result = $wpdb->get_row("SELECT id FROM fape_url_logs WHERE url = '" . $url . "'", 'ARRAY_A');
    $wpdb->insert('fape_url_logs', array(
        'url' => $url,
        'time' => time()
    ));
}

function fapello_get_urls_log($url)
{
    global $wpdb;
    $time = 604800; // 7 days
    $results = $wpdb->get_row("SELECT * FROM fape_url_logs WHERE url = '$url' order by id desc limit 1", 'ARRAY_A');
    foreach( $results as $result ) {
       $tm =  $result->time;
       if((time() - $tm) > $time)
       {
           return 1;
       }
    }
    return 0;
}

function the_slug_exists($post_name) {
    global $wpdb;

    $result = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_name = '" . $post_name . "'", 'ARRAY_A');

    if($result) {
       // echo $result['ID'];
        return $result['ID'];
    } else {
        return false;
    }
}