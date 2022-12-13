<?php


global $wpdb;

// when this page run -

// update total likes
if(1)
{
    echo 'When this page run.. It will update - total likes and following count';
    $results= $wpdb->get_results("SELECT * FROM `fape_model` order by time limit 100");
    foreach ($results as $result)
    {

       // print_r($result);
        $total_likes = 0;
        $total_followers = 0;

        $time = time();

        $xxy = $wpdb->get_var("SELECT count(*) FROM `fape_likes` where model_id = '{$result->id}'");
        $total_likes = intval($xxy);

        $qqs = $wpdb->get_var("SELECT count(*) FROM `fape_follower` where model_id = '{$result->id}'");
        $total_followers = intval($qqs);

        $wpdb->query("UPDATE `fape_model` SET `time`='{$time}',`ttl_likes`='{$total_likes}',`ttl_follows`='{$total_followers}' WHERE id = '{$result->id}'");
    }

   // $qqs = $wpdb->get_results("SELECT * FROM `fape_media` order by time desc  limit 100");


}