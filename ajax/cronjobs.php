<?php

// create_term
add_action( 'created_Model', 'misha_save_term_fields');
add_action( 'edited_Model', 'misha_save_term_fields');
add_action( 'saved_Model', 'misha_save_term_fields');

function misha_save_term_fields( $term_id) {

    $t = get_term( $term_id );
    $name = $t->name;
    $slug = $t->slug;
    $taxonomy = $t->taxonomy;
    global $fapello_ajax;
    $postid = 0;
    $tpdt = $fapello_ajax->get_post_id_by_term_id($term_id);
    if($tpdt)
    {
        $postid = $tpdt;
    }

   $fapello_ajax->create_model($name,$postid);
    // when create new term
}


// time - 5min

// wp post to media images

//tag-model description = json object

add_action( 'save_post', 'my_save_post_function', 10, 3 );

function my_save_post_function( $post_ID, $post, $update ) {
    add_media_data($post_ID,$post->post_content);
    global $wpdb;
    global $fapello_ajax;
    $xq = "SELECT * FROM `fape_model` where post_id = '0' order by id desc limit 12";
    $results = $wpdb->get_results($xq);
    foreach ($results as $result)
    {
        $tagname = $result->name;
        $post_x = $fapello_ajax->get_post_id_by_term_name($tagname);
        if($post_x)
        {
            $wpdb->query("UPDATE `fape_model` SET `post_id`='{$post_x}' WHERE id = {$result->id}");
        }
    }
}



add_action( 'post_updated', 'check_updated_post_name', 10, 3 );
function check_updated_post_name( $post_ID, $post_after, $post_before ) {
 //  print_r($post_after);
  // print_r($post_before);
    global $fapello_ajax;

    if ( $post_after->post_content == $post_before->post_content ) {
        return 1;
    }

    add_media_data($post_ID,$post_after->post_content);
}


function add_media_data($post_id,$post_content)
{
    global $fapello_ajax;
    $model_id = 0;
    $model_terms = wp_get_post_terms($post_id,'Model');
    foreach ($model_terms as $model_term) {
        $model_name = $model_term->name;
        $model_url = $model_term->slug;
        $termid = $model_term->term_id;
        $taxaid = $model_term->term_taxonomy_id;
        $description = $model_term->description;

        $model_data = $fapello_ajax->get_model_by_name($model_name);
        foreach ($model_data as $model_datum)
        {
            $model_id = $model_datum->id;
        }


    }

    if($model_id)
    {
        $imageNvideo =  ImageVideUrlFinder($post_content);
        foreach ($imageNvideo['images'] as $imageurl)
        {
            $fapello_ajax->add_media($model_id,$imageurl,$model_url);
        }
        foreach ($imageNvideo['videos'] as $imageurl)
        {
            $fapello_ajax->add_media($model_id,$imageurl,$model_url,'video');
        }
    }
}



function image_url_finder($strx)
{
    $pattarn = '/(https?:\/\/.*\.(?:png|jpg))/i';

    if(preg_match($pattarn,$strx,$mntc))
    {
        return $mntc[1];
    }


}
