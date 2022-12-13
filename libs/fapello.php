<?php

// $wpdb->get_results();
// $wpdb->num_rows;
// $wpdb->_escape

class fapello{

    public $userid = 0;
    public $admin = 0;
    public $wpdb = null;


    function __construct($wpdb,$userid)
    {
        $this->wpdb = $wpdb;
        $this->userid = $userid;
    }

    // comments
    function add_comment($media_id,$comment,$userid,$ckey = '')
    {
        if(!$this->userid)
        {
            return 0;
        }
        $ckey = md5($comment);
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_comments` WHERE where user_id = '{$userid}' and media_id = '{$media_id}' and ckey = '{$ckey}'");
        if(count($xquery)>0)
        {

        }else{
            $comment = $this->wpdb->_escape($comment);
            $time = time();
            $this->wpdb->get_results("INSERT INTO `fape_comments`(`media_id`, `user_id`, `comments`, `time`, `ckey`) 
VALUES ('{$media_id}','{$userid}','{$comment}','{$time}','{$ckey}')");
            return 1;
        }
        return  0;
    }

    function update_comment($comment_id,$comment)
    {
        if(!$this->userid)
        {
            return 0;
        }
        $ckey = md5($comment);
        $time = time();
        $comment = $this->wpdb->_escape($comment);
        $this->wpdb->get_results("UPDATE `fape_comments` SET `comments`='{$comment}',`time`='{$time}',`ckey`='{$ckey}' WHERE id = '{$comment_id}'");
        return 1;
    }
    function delete_comment($comment_id)
    {
        if(!$this->userid)
        {
            return 0;
        }
        if($this->admin)
        {
            $this->wpdb->query("DELETE FROM `fape_comments` WHERE id = '{$comment_id}'");
        }else{
            $this->wpdb->query("DELETE FROM `fape_comments` WHERE id = '{$comment_id}' and user_id = '{$this->userid}'");
            return 0;
        }


        return 1;
    }

    // likes

    function is_liked_media($media_id)
    {
        $xquery = $this->wpdb->get_var("SELECT count(*) FROM `fape_likes` where user_id = '{$this->userid}' and media_id = '{$media_id}'");


       // $xquery = $this->wpdb->get_results("SELECT * FROM `fape_likes` where user_id = '{$this->userid}' and media_id = '{$media_id}'");
        if($xquery)
        {
            return 1;
        }else{
            return 0;
        }
    }


    function get_model_id_by_media_id($media_id)
    {
        $xq = $this->wpdb->get_var("SELECT model_id FROM `fape_media` where id = '{$media_id}'");
        return $xq;
    }

    function add_likes($media_id,$like =1)
    {
        if(!$this->userid)
        {
            return 0;
        }
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_likes` where user_id = '{$this->userid}' and media_id = '{$media_id}'");
        if(count($xquery)>0)
        {

        }else{
            $time = time();
            $model_id = $this->get_model_id_by_media_id($media_id);

            $this->wpdb->query("INSERT INTO `fape_likes`(`media_id`, `user_id`, `type`, `time`, `model_id`) 
VALUES ('{$media_id}','{$this->userid}','$like','{$time}','{$model_id}')");
            return 1;
        }
        return  0;
    }


    // media
    function add_media($model_id,$url,$model_slug,$type = 'image')
    {
        if(!$this->userid)
        {
            return 0;
        }
        $link = $this->wpdb->_escape($url);
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_media` where model_id = '{$model_id}' and link = '{$link}'");
        if(count($xquery)>0)
        {
            return 0;
        }else{
            $time = time();
            $this->wpdb->query("INSERT INTO `fape_media`(`model_id`, `time`, `model_name`, `type`, `link`) VALUES ('{$model_id}','{$time}','{$model_slug}','{$type}','{$link}')");
        }
        return 1;
    }

    function get_post_id_by_term_id($term_id,$onlyid = 1)
    {

        $args = array(
            'posts_per_page' => 1,
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'tax_query'      => array(
                array(
                    // 'taxonomy'  => 'post_tag',
                    'taxonomy'  => 'Model',
                    'field'     => 'term_id',
                    // 'terms'     => 'bablu'
                    'terms'     => $term_id
                )
            )
        );

        $postslist = get_posts( $args );
        foreach ($postslist as $post)
        {
          if($onlyid)
          {
              return $post->ID;
          }else{
              return  $post;
          }
        }
        return  0;
    }
    function get_post_id_by_term_name($term_name,$onlyid = 1)
    {

        $args = array(
            'posts_per_page' => 1,
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'tax_query'      => array(
                array(
                    // 'taxonomy'  => 'post_tag',
                    'taxonomy'  => 'Model',
                    'field'     => 'name',
                    // 'terms'     => 'bablu'
                    'terms'     => $term_name
                )
            )
        );

        $postslist = get_posts( $args );
        foreach ($postslist as $post)
        {
          if($onlyid)
          {
              return $post->ID;
          }else{
              return  $post;
          }
        }
        return  0;
    }


    function get_media_byid($mediaid)
    {
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_media` where id = '{$mediaid}'");
        return  $xquery;
    }



    // model
    function get_model_by_id($model_id)
    {

        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_model` WHERE id = '{$model_id}'");

        return $xquery;
    }    // model
    function get_model_by_name($model_name,$returnType = OBJECT)
    {
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_model` WHERE name = '{$model_name}'",$returnType);

        return $xquery;
    }

    function create_model($model_name,$post_id,$data = '')
    {
        if(!$this->userid)
        {
            return 0;
        }
        $time = time();
        $model_name = $this->wpdb->_escape($model_name);
        $xquery = $this->wpdb->get_results("SELECT * FROM `fape_model` where name = '{$model_name}'");
        if(count($xquery)>0)
        {
            return 0;
        }else{
            $data = $this->wpdb->_escape($data);
            $this->wpdb->query("INSERT INTO `fape_model`(`post_id`, `name`, `data`, `time`) 
VALUES ('{$post_id}','{$model_name}','{$data}','{$time}')");
        }
        return 1;
    }
    function update_model($model_id,$data)
    {
        if(!$this->userid)
        {
            return 0;
        }
        $time = time();
        $data = $this->wpdb->_escape($data);
        $this->wpdb->query("UPDATE `fape_model` SET `data`='{$data}',`time`='{$time}' WHERE id = '{$model_id}'");
        return 1;
    }

    function is_following($model_id)
    {

          $xquery = $this->wpdb->get_var("SELECT count(*) FROM `fape_follower` where model_id = '{$model_id}' and user_id = '{$this->userid}'");

          return $xquery;
    }

    function follow_model($model_id,$follow =1)
    {
        $model_id = (int)$model_id;
        if(!$this->userid)
        {
            return 0;
        }
        $time = time();

        $is_following = $this->is_following($model_id);

        if($is_following)
        {
            if($follow)
            {

            }else{
                $this->wpdb->query("DELETE FROM `fape_follower` WHERE user_id = '{$this->userid}' and model_id = '{$model_id}'");
            }
        }else{
            $this->wpdb->query("INSERT INTO `fape_follower`(`model_id`, `user_id`, `time`) 
VALUES ('{$model_id}','{$this->userid}','{$time}')");
        }

        return 1;
    }
}


class fapello_ajax extends fapello{

    function a_get_homepage_posts($page =0,$item_perpage = 10)
    {
        $xofset = $page * $item_perpage;
        $args = array(
            'numberposts'      => $item_perpage,
            'offset'           => $xofset,
            'post_status'      => 'publish',
        );

        $output_str = '';
       // $recent_posts = wp_get_recent_posts($args);
        $recent_posts = new WP_Query($args);

        if ( $recent_posts -> have_posts() ) :
            while ( $recent_posts -> have_posts() ) :
                $recent_posts -> the_post();
                get_template_part( 'loop-templates/content', '' );
            endwhile;
        endif;
    }
    function a_get_random_posts($page =0,$item_perpage = 10)
    {
        $xofset = $page * $item_perpage;
        $args = array(
            'numberposts'      => $item_perpage,
            'offset'           => $xofset,
            'post_status'      => 'publish',
            'orderby' => 'rand'
        );

        $output_str = '';
       // $recent_posts = wp_get_recent_posts($args);
        $recent_posts = new WP_Query($args);

        if ( $recent_posts -> have_posts() ) :
            while ( $recent_posts -> have_posts() ) :
                $recent_posts -> the_post();
                get_template_part( 'loop-templates/content', '' );
            endwhile;
        endif;
    }

    /**
     * @param int $page
     * @param int $item_perpage
     * @param string $order_by id|time|ttl_likes|ttl_follows|ttl_views|ttl_comments
     */

    function a_get_top_models_by($page =0,$item_perpage = 10,$order_by = 'time')
    {

        $xofset = $page * $item_perpage;
        $results =$this->wpdb->get_results("SELECT * FROM `fape_model` where post_id != 0 order by $order_by asc limit $xofset,$item_perpage");
        $post_ids = [];
        foreach ($results as $result)
        {
            $post_ids[] = $result->post_id;
           // print_r($post);
           // $post->the_post();
           // get_template_part( 'loop-templates/content', '' );
        }

        $args = array(
            'post__in'      => $post_ids
        );
        $recent_posts = new WP_Query( $args );
        if ( $recent_posts -> have_posts() ) :
            while ( $recent_posts -> have_posts() ) :
                $recent_posts -> the_post();
                get_template_part( 'loop-templates/content', '' );
            endwhile;
        endif;
    }

    function get_image_url_by_model_id($model_id,$limit =1)
    {
        $images = [];
        $xq = "SELECT * FROM `fape_media` where model_id = '{$model_id}' and type = 'image' order by id desc limit $limit";
        $results =$this->wpdb->get_results($xq);
        foreach ($results as $result)
        {
            if($limit == 1)
            {
                return $result->link;
            }
            $images[] = $result->link;
        }
        return  $images;
    }
    function get_image_data_by_media_id($media_id,$limit =1)
    {
        $images = [];
        $xq = "SELECT * FROM `fape_media` where id = '{$media_id}'";
        $results =$this->wpdb->get_results($xq);
        foreach ($results as $result)
        {
            if($limit == 1)
            {
                return $result;
            }
        }
    }

    function a_get_medias_by($model_id = 0,$page =0,$item_perpage = 10,$order_by = 'model_id')
    {
        global $HTTP;
        $xofset = $page * $item_perpage;

        $xq = "SELECT * FROM `fape_media` where model_id = '{$model_id}' order by $order_by asc limit $xofset,$item_perpage";
        if($model_id == 0)
        {
           $xq = "SELECT * FROM `fape_media` order by id asc limit $xofset,$item_perpage";
        }

        $output = "";

        $results =$this->wpdb->get_results($xq);
       // print_r($results);

        foreach ($results as $result)
        {

            $model_url = "/Model/{$result->model_name}/{$result->id}";

            if($result->type == 'image')
            {
                $media_html = <<<sdklfjhdslkifhdsjksdlkfsdjioh
    <img src="{$result->link}" class="w-full h-full absolute object-cover inset-0">
sdklfjhdslkifhdsjksdlkfsdjioh;

            }else{
                $media_html = <<<sdklfjhdslkifhdsjksdlkfsdjioh
   <video width="400" controls="controls" preload="metadata">
  <source src="{$result->link}#t=0.5" type="video/mp4">
</video>
sdklfjhdslkifhdsjksdlkfsdjioh;
            }


            $output .= <<<sddlfhfdspofghdpofipdshgiofids
<div>
            <a href="{$HTTP}{$model_url}">
                <div class="max-w-full lg:h-64 h-40 rounded-md relative overflow-hidden uk-transition-toggle" tabindex="0">
                {$media_html}
                </div>
            </a>
</div>
sddlfhfdspofghdpofipdshgiofids;

        }
        return $output;
    }

    function get_model_data_by_post_id($post_id)
    {
        global $wpdb;
        $xq = $wpdb->get_row( "SELECT * FROM `fape_model` where post_id = '{$post_id}'");
        return $xq;
    }

    function get_new_media_url_by_model_id($model_id,$limit =3)
    {
        global $wpdb;
        $xq = $wpdb->get_results("SELECT * FROM `fape_media` where model_id = '{$model_id}' and type = 'image' order by id desc limit $limit");

       $urls = [];
        foreach ($xq as $result)
        {
            $urls[] = ['id'=> $result->id,'url'=>$result->link];
        }
        return $urls;
    }

    function a_get_recent_comments($page = 0,$limit =10)
    {
        global $fapello_ajax;
        global $HTTP;
        $pgx = $page * $limit;
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM `fape_comments` order by id desc limit $pgx,$limit");
        $cmnt_html = '';
        foreach ($results as $result)
        {

            $user = get_user_by('id',$result->user_id);
            $image_url = esc_url(get_avatar_url($result->user_id));

            $name = $user->user_login;

            $img_html = "";
            $midx = $fapello_ajax->get_image_data_by_media_id($result->media_id);
            $imgx = $midx->link;
            if(strlen($imgx) >6)
            {
                $img_html = <<<sdfhdsiufgdsifusdgaifugdsauifgiusda
<a href="{$HTTP}/Model/{$midx->model_name}/">
<img style="max-width: 400px;max-height: 400px" src="{$imgx}" alt="Veroodle-2" class="rounded-md w-full h-full img_feed">
</a>
sdfhdsiufgdsifusdgaifugdsauifgiusda;

            }

            $cstr = wp_filter_nohtml_kses($result->comments);
            $cmnt_html .= <<<slkkhfdslkfghdsiufgdsiufgdsfidsgfiudsfgdisufgdsoiufpodsfpodsjfdspo
 <div class="grid grid-cols-1 gap-2 p-2">
  {$img_html}
<div class="flex">

											<div class="w-10 h-10 rounded-full relative flex-shrink-0">
												<img title="{$name}" src="{$image_url}" alt="{$name}" class="absolute h-full rounded-full w-full">
											</div>
											<div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20  dark:bg-gray-800 dark:text-gray-100">
												<p class="leading-6">{$cstr}</p>
												<div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 dark:bg-gray-700"></div>
											</div>
</div>
</div>
slkkhfdslkfghdsiufgdsiufgdsfidsgfiudsfgdisufgdsoiufpodsfpodsjfdspo;


        }

        return $cmnt_html;
    }


    function a_get_comments_by_media_id($media_id,$page = 0,$limit =10)
    {
        $pgx = $page * $limit;
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM `fape_comments` where media_id = '{$media_id}' order by id desc limit $pgx,$limit");

        $cmnt_html = '';
        foreach ($results as $result)
        {

            $user = get_user_by('id',$result->user_id);
            $image_url = esc_url(get_avatar_url($result->user_id));

            $name = $user->user_login;

            $cstr = wp_filter_nohtml_kses($result->comments);
            $cmnt_html .= <<<slkkhfdslkfghdsiufgdsiufgdsfidsgfiudsfgdisufgdsoiufpodsfpodsjfdspo
<div class="flex">
											<div class="w-10 h-10 rounded-full relative flex-shrink-0">
												<img src="{$image_url}" alt="" class="absolute h-full rounded-full w-full">
											</div>
											<div class="text-gray-700 py-2 px-3 rounded-md bg-gray-100 h-full relative lg:ml-5 ml-2 lg:mr-20  dark:bg-gray-800 dark:text-gray-100">
												<p class="leading-6">{$cstr}</p>
												<div class="absolute w-3 h-3 top-3 -left-1 bg-gray-100 transform rotate-45 dark:bg-gray-700"></div>
											</div>
</div>
slkkhfdslkfghdsiufgdsiufgdsfidsgfiudsfgdisufgdsoiufpodsfpodsjfdspo;


        }

        return $cmnt_html;
    }


    function a_get_single_modelBy_postid($post_ids)
    {
        $args = array(
            'post__in'      => $post_ids,
            'orderby' => 'rand'
        );

        $output = '';

        $recent_posts = new WP_Query( $args );
        if ( $recent_posts -> have_posts() ) :
            while ( $recent_posts -> have_posts() ) :
                $recent_posts -> the_post();
                $post_url = esc_url( get_permalink() );
                $the_title = get_the_title();
                $the_id = get_the_ID();
                $img = "data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20364%20205'%3E%3C/svg%3E";
                if (has_post_thumbnail( $the_id ) ):
                    $img = get_the_post_thumbnail_url($the_id, 'post-thumbnail');
                endif;

                $output .= <<<sdfhgdsioufgsduifgsdauifogskfgsadlfsgdlk
<li tabindex="-1" class="uk-active">
								<div class="bg-gray-200 max-w-full lg:h-64 h-52 rounded-lg relative overflow-hidden">
									<a href="{$post_url}/">
										<img src="{$img}" class="w-full h-full absolute object-cover inset-0">
									</a>

									<div class="absolute bottom-0 p-4 w-full custom-overly1">
										<div class="flex justify-between align-bottom flex-wrap text-white">
											<div class="w-full truncate text-lg"> {$the_title} </div>
											<div class="leading-5 text-sm">
												<div>  </div>
											   
											</div>
										</div>
									</div>
								</div>
</li>

sdfhgdsioufgsduifgsdauifogskfgsadlfsgdlk;
              //  get_template_part( 'loop-templates/content', '' );
            endwhile;
        endif;

        return $output;
    }


    function  a_get_media_likes_by_user($userid,$page = 0,$limit =15)
    {
        global $HTTP;
        $pgx = $limit * $page;
        $html = '';

        // $results = $this->wpdb->get_results("SELECT m.name,m.id, me.id,me.link,l.*  FROM fape_media AS me  LEFT JOIN fape_likes AS l  ON l.media_id=me.id and user_id = '{$userid}'  LEFT JOIN fape_model AS m ON me.model_id = m.id order by id desc limit $pgx,$limit");
        $results = $this->wpdb->get_results("
        SELECT mo.*, mo.name, me.id as 'media_id', me.link,me.type
FROM
     fape_likes l
INNER JOIN
     fape_media me on l.media_id = me.id
LEFT JOIN 
     fape_model mo on mo.id = me.model_id
WHERE 
     l.user_id = '{$userid}'
        order by id desc limit $pgx,$limit");

        foreach ($results as $result)
        {

           $slug =  sanitize_title($result->name);
          //  $html .= "<li><img src='{$result->link}'> <a href='{$HTTP}/Model/{$slug}/{$result->media_id}'>{$result->name}</a> </li>";

            $media_url = $HTTP."/Model/{$slug}/{$result->media_id}";
            $model_url = $HTTP."/Model/{$slug}";
            $html .= <<<dflkhfdskljfhdsfgdsifdsfhdsffdsoiuf
<div>
						<div class="bg-yellow-400 max-w-full lg:h-60 h-48 rounded-lg relative overflow-hidden shadow uk-transition-toggle">
							<a href="{$media_url}">
								<img src="{$result->link}" class="w-full h-full absolute object-cover inset-0">
							</a>
							
							<div class="flex flex-1 items-center absolute bottom-0 w-full p-3 text-white custom-overly1">
								<a href="{$model_url}" class="lg:flex flex-1 items-center">
									
									<div class="p-1 rounded-full transform -rotate-2 hover:rotate-3 transition hover:scale-105 m-0.5 mr-2">
										<img src="{$result->link}" class="bg-gray-200 border border-white rounded-full w-8">
									</div>
									
									<div> {$result->name} </div>
								</a>
								
							</div>

						</div>
</div>
dflkhfdskljfhdsfgdsifdsfhdsffdsoiuf;


        }
        return $html;
    }


    function  a_get_subs_by_userid($page = 0,$limit =15,$new_image =0)
    {
        global $HTTP;
        $pgx = $limit * $page;
        $html = '';

        // $results = $this->wpdb->get_results("SELECT m.name,m.id, me.id,me.link,l.*  FROM fape_media AS me  LEFT JOIN fape_likes AS l  ON l.media_id=me.id and user_id = '{$userid}'  LEFT JOIN fape_model AS m ON me.model_id = m.id order by id desc limit $pgx,$limit");
        $results = $this->wpdb->get_results("
       SELECT mo.*
FROM
     fape_follower f 
INNER JOIN
     fape_model mo on mo.id = f.model_id
WHERE 
     f.user_id = '{$this->userid}'
order by id desc limit $pgx,$limit");

        foreach ($results as $result)
        {
           $slug =  sanitize_title($result->name);
          //  $html .= "<li><img src='{$result->link}'> <a href='{$HTTP}/Model/{$slug}/{$result->media_id}'>{$result->name}</a> </li>";

            $media_url = $HTTP.'/Model/'.sanitize_title($result->name);
            $image = $result->image;


            if($new_image)
            {

                global $fapello_ajax;
               $html .=  $fapello_ajax->a_get_medias_by($result->id);
            }else {


                $html .= <<<dflkhfdskljfhdsfgdsifdsfhdsffdsoiuf
									<div class="flex items-center justify-between py-3">
									<div class="flex flex-1 items-center space-x-4">
											<a href="{$media_url}">
												<img src="{$image}" class="bg-gray-200 rounded-full w-10 h-10">
											</a>
											<div class="flex flex-col">
												<a href="{$media_url}"><span class="block capitalize font-semibold"> {$result->name} </span></a>
												<span class="block capitalize text-sm"> OnlyFans </span>
											</div>
										</div>
										<a href="?act=unfollow&model_id={$result->id}" class="bg-pink-500 text-white border font-semibold px-4 py-1 rounded-full hover:bg-blue-600 hover:text-white follow_button" id="follow_456" model_id="456">Unfollow</a>
									</div>
dflkhfdskljfhdsfgdsifdsfhdsffdsoiuf;
            }

        }
        return $html;
    }

    function a_get_total_media_count_by_model_id($model_id,$number_only =1)
    {
        global $wpdb;
        $ttl = 0;
        $xq = "SELECT count(*) FROM `fape_media` WHERE model_id = '{$model_id}'";
        return $wpdb->get_var($xq);
    }


    function update_model_table_data($model_id,$key,$value)
    {
        $value = $this->wpdb->_real_escape($value);
        $this->wpdb->query("UPDATE `fape_model` SET `$key`='$value' WHERE id = '{$model_id}'");
    }

    function a_get_model_profile_by_term_data($termdata,$js_strx = '',$show_content_id=  1)
    {

        global $HTTP;
        $content_div_id = 'content';
        if(!$show_content_id)
        {
            $content_div_id = '';
        }

        global $fapello_ajax;

        $t_id = $termdata->term_id;
        $t_name = $termdata->name;
        $t_slug = $termdata->slug;
        $t_description = $termdata->description;

        $fapello_model = new fapello_model();
        if(isJson($t_description))
        {
            $js_data = json_decode($t_description,1);
            $fapello_model->tag_description_to_data_build($js_data);
        }

        $the_title = $t_name;
        $model_json_str = $t_description;
        $model_json = [];

        if(isJson($model_json_str))
        {
            $model_json = json_decode($model_json_str,1);
        }
        $model_name = '';
        $model_id = '';
        $model_url = '';
        $model_picture = '';
        $social_links = '';
        $model_total_media = '';


        $model_total_likes = 0;
        $model_total_follow = 0;
        $model_datas = $fapello_ajax->get_model_by_name($the_title);
        foreach ($model_datas as $model_data)
        {
            $model_id = $model_data->id;
            $model_name = $the_title;
            $model_url = $HTTP.'/Model/'. $t_slug;
            $model_total_likes = $model_data->ttl_likes;
            $model_total_follow = number_shorten( $model_data->ttl_follows,0);
            $fapello_model->db_to_model((array)$model_data);
            if(isset($model_data->image))
            {
                $model_picture = $model_data->image;
            }else{

                if($model_data->post_id)
                {
                    $model_picture = isset($model_data->image) ? $model_data->image : get_the_post_thumbnail_url($model_data->post_id);
                    $fapello_ajax->update_model_table_data($model_id,'image',$model_picture);
                }
                // update model picture
            }
        }

        $fbg_clr = '';
        $fbg_txt = 'follow';
        $follow_btn = '';
        if($this->userid)
        {

            $is_following = $fapello_ajax->is_following($model_id);



            if($is_following)
            {
                $fbg_clr = 'background-color: black;';
                $fbg_txt = $model_total_follow. ' following';
            }else{
                $fbg_clr = 'background-color: green;';
                if(isset($_REQUEST['act']))
                {
                    if($_REQUEST['act'] == 'follow')
                    {
                        $fapello_ajax->follow_model($model_id);
                    }
                }
            }

            $follow_btn = <<<fhdsfjikdsgfdskfogdsofods
<a href="?act=follow" style="{$fbg_clr}" class="bg-pink-500 shadow-sm p-2 pink-500 px-6 rounded-md text-white hover:text-white hover:bg-pink-600 follow_button" id="follow_9050" model_id="9050">{$fbg_txt}</a>
fhdsfjikdsgfdskfogdsofods;
        }else{
            $follow_btn = '<a style="'.$fbg_clr.'" class="bg-pink-500 shadow-sm p-2 pink-500 px-6 rounded-md text-white hover:text-white hover:bg-pink-600 follow_button" id="follow_9050" model_id="9050">'.$fbg_txt.'</a>';
        }


       $model_total_media =  $fapello_ajax->a_get_total_media_count_by_model_id($model_id);
        $img = "data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20364%20205'%3E%3C/svg%3E";
        $HTTP = site_url();
        // $model_contents = '';
        $model_contents = $fapello_ajax->a_get_medias_by($model_id);
        $social_links = $fapello_model->get_socal_links_html();
        $about = esc_html($fapello_model->about);

        return <<<fhdsfjikdsgfdsiuofogsdfpiudsgfiusdfgdsuiofgdsiufdsgfuipgjdogrtiyr
<script>
{$js_strx}

ajax_data.model_id = {$model_id};
</script>

 <div class="flex lg:flex-row flex-col items-center lg:py-8 lg:space-x-8">
        <div>
            <div class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-1 rounded-full m-0.5 mr-2  w-56 h-56 relative overflow-hidden uk-transition-toggle">
                <a href="{$model_url}"><img src="{$model_picture}" class="bg-gray-200 border-4 border-white rounded-full w-full h-full dark:border-gray-900"></a>
            </div>
        </div>
        <div class="lg:w/8/12 flex-1 flex flex-col lg:items-start items-center">
            <h2 class="font-semibold lg:text-2xl text-lg mb-2 mt-4">{$model_name}</h2>
            <p class="flex font-semibold mb-3 space-x-2  dark:text-gray-10">{$about}</p>
            {$social_links}
            <div class="capitalize flex font-semibold space-x-3 text-center text-sm my-2">
                {$follow_btn}
                <a href="#" class="bg-blue-500 shadow-sm p-2 pink-500 px-6 rounded-md text-white hover:text-white hover:bg-blue-600">Discuss</a>
                <a href="#" class="flex items-center space-x-2"><div class="p-2 rounded-full text-black" id="like_9050"><svg aria-label="Like" color="#000000" fill="#000000" height="24" role="img" viewBox="0 0 48 48" width="24"><path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg></div></a>
            </div>
            <div class="divide-gray-300 divide-transparent divide-x grid grid-cols-2 lg:text-left lg:text-lg mt-3 text-center w-full dark:text-gray-100">
                <div class="flex lg:flex-row flex-col"> {$model_total_media} <strong class="lg:pl-2">Media</strong></div>
                <div class="lg:pl-4 flex lg:flex-row flex-col" id="count_likes_9050"> {$model_total_likes} <strong class="lg:pl-2">Likes</strong></div>
            </div>
        </div>
        <div class="w-20"></div>
    </div>
    <hr class="uk-divider-icon">

    <div id="{$content_div_id}" class="my-6 grid lg:grid-cols-4 grid-cols-2 gap-1.5 hover:text-yellow-700 uk-link-reset">
    <!-- ajax content will added here -->
    {$model_contents}
    </div>



fhdsfjikdsgfdsiuofogsdfpiudsgfiusdfgdsuiofgdsiufdsgfuipgjdogrtiyr;

    }

    function a_get_top10_models($pagenumber =0,$order_by = 'id',$limit=10)
    {
        global $HTTP;
        $models = [];
        $post_ids = [];

        $pagex = $pagenumber * $limit;


        $xq = "SELECT * FROM `fape_model` where post_id != '0' order by $order_by desc limit $pagex, $limit";


        $results = $this->wpdb->get_results($xq);
     //   print_r($results);

        $i = 0;
        foreach ($results as $result)
        {
            $post_ids[] = $result->post_id;
        }

        $output = $this->a_get_single_modelBy_postid($post_ids);
        return $output;
    }

    function a_get_videos_list($pagenumber = 0,$limit = 8)
    {
        global $HTTP;
        $theme_dir = get_template_directory_uri();

        $pagex = $pagenumber * $limit;
        $xq = "SELECT * FROM `fape_media` where type = 'video' order by id desc limit $pagex,$limit";

        $output = '';

        $results = $this->wpdb->get_results("$xq");
        $i = 0;
        $items = [];
        $xmdl = '';
        foreach ($results as $result)
        {
            // = $this->a_get_new_media_by_modelID($result->id);

            $media_url ="{$HTTP}/Model/{$result->model_name}/{$result->id}/";
            $items[] = <<<sdfhydsfiugdsfiusdgfiusd
<div>
						<div class="bg-yellow-400 max-w-full lg:h-60 h-48 rounded-lg relative overflow-hidden shadow uk-transition-toggle">
							<a href="{$media_url}">
								
<video width="400" controls="controls" preload="metadata">
  <source src="{$result->link}#t=0.5" type="video/mp4">
</video>
							</a>
							<a href="{$media_url}" class="absolute flex h-full items-center justify-center w-8 w-full  bg-black bg-opacity-10">
								<img src="{$theme_dir}/assets/images/icon-play.svg" alt="" class="w-16 h-16 -mt-5">
							</a>
							<div class="flex flex-1 items-center absolute bottom-0 w-full p-3 text-white custom-overly1">
								<a href="{$media_url}" class="lg:flex flex-1 items-center">
									<div class="p-1 rounded-full transform -rotate-2 hover:rotate-3 transition hover:scale-105 m-0.5 mr-2">
										<img src="{$theme_dir}/assets/images/avatar.jpg" class="bg-gray-200 border border-white rounded-full w-8">
									</div>
									<div> {$result->model_name} </div>
								</a>
								
							</div>

						</div>
</div>
sdfhydsfiugdsfiusdgfiusd;


        }
        $newarr = array_chunk($items,3);
        foreach ($newarr as $xar)
        {
            $output .= '<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">'. implode(' ',$xar) . '</div>';

        }

        return $output;

    }


    function  a_get_new_media_by_modelID($model_id,$limit = 1,$default = '')
    {

        global $HTTP;
        $output = '';
        $xq = "SELECT * FROM `fape_media` where model_id = '{$model_id}' and type = 'image' order by id desc limit $limit";
        $resutls = $this->wpdb->get_results($xq);
        foreach ($resutls as $resutl)
        {

            $media_url ="{$HTTP}/Model/{$resutl->model_name}/{$resutl->id}/";
            $output .= <<<sdfhydsfiugdsfiusdgfiusd
<div>
						<div class="bg-yellow-400 max-w-full lg:h-60 h-48 rounded-lg relative overflow-hidden shadow uk-transition-toggle">
							<a href="{$media_url}">
								<img src="{$resutl->link}" class="w-full h-full absolute object-cover inset-0">
							</a>
							
							<div class="flex flex-1 items-center absolute bottom-0 w-full p-3 text-white custom-overly1">
								<a href="{$media_url}" class="lg:flex flex-1 items-center">
									<div class="p-1 rounded-full transform -rotate-2 hover:rotate-3 transition hover:scale-105 m-0.5 mr-2">
										<img src="{$resutl->link}" class="bg-gray-200 border border-white rounded-full w-8">
									</div>
									<div> {$resutl->model_name} </div>
								</a>
								
							</div>

						</div>
</div>
sdfhydsfiugdsfiusdgfiusd;

        }
        return $output;
    }

    function a_get_last_3_model($limit =3)
    {
        global $HTTP;
        $html = '';
        $xq = "SELECT * FROM `fape_model` where post_id != 0 order by id desc limit $limit";
        $results = $this->wpdb->get_results("$xq");
        foreach ($results as $result)
        {
            $model_name = str_replace('-',' ',$result->name);
            $model_url = $HTTP.'/Model/'. sanitize_title($result->name);
            $model_image = $result->image;
            if(strlen($model_image)<5)
            {
                global $fapello_ajax;
               $image_url =  $fapello_ajax->get_image_url_by_model_id($result->id);
               if(strlen($image_url)>10)
               {
                   $model_image = $image_url;
                   $fapello_ajax->update_model_table_data($result->id,'image',$model_image);
               }
            }


            $html .= <<<gfpdogheorhgteorhgdosihodsfghds
<li>
						<div class="flex flex-1 items-center w-full text-white">
							<a href="{$model_url}" class="lg:flex flex-1 items-center hidden">
								<div class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-1 rounded-full transform -rotate-2 hover:rotate-3 transition hover:scale-105 m-0.5 mr-2">
									<img src="{$model_image}" class="bg-gray-200 border border-white rounded-full w-8">
								</div>
								<div> {$model_name} </div>
							</a>
								
						</div>
</li>
gfpdogheorhgteorhgdosihodsfghds;

        }
        return $html;
    }

    function a_get_model_media_list($type = 'trending',$pagenumber=0,$limit =8)
    {

        $xpage = $pagenumber * $limit;


        $order_by = 'time';
        $output = '';

        $xq = "SELECT * FROM `fape_model` where post_id != 0 order by $order_by desc limit $xpage,$limit";

        if($type =='top-likes')
        {
            $xq = "SELECT * FROM `fape_model` where post_id != 0 order by ttl_likes desc limit $xpage,$limit";
        }elseif ($type =='top-followers')
        {
            $xq = "SELECT * FROM `fape_model` where post_id != 0 order by ttl_follows desc limit $xpage,$limit";
        }elseif ($type =='random')
        {
            $xq = "SELECT * FROM `fape_model` where post_id != 0 order by rand() desc limit $xpage,$limit";
        }

        $results = $this->wpdb->get_results("$xq");

        $i = 0;
        $items = [];
        $xmdl = '';
        foreach ($results as $result)
        {
           // echo "<h1>xyx</h1>";
            $items[] = $this->a_get_new_media_by_modelID($result->id);
           // $xmdl .= '<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">'. $this->a_get_new_media_by_modelID($result->id) . "</div>";
        }
        $newarr = array_chunk($items,4);
        foreach ($newarr as $xar)
        {
            $output .= '<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">'. implode(' ',$xar) . '</div>';

        }

        return $output;
    }


}
class fapello_model{

    public $id = 0;
    public $name = '';
    public $total_followers = 0;
    public $total_likes = 0;
    public $total_views = 0;
    public $total_comments = 0;

    public $url = '';

    public $image = '';
    public $age = 0;
    public $twitter = '';
    public $onlyfans = '';
    public $instagram = '';
    public $about = '';

    public $post_id = 0;
    public $model_id = 0;
    public $model_name = '';

    function get_json()
    {
        $model_data = [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'age' => $this->age,
            'image' => $this->image,
            'ttl_followers' => $this->total_followers,
            'ttl_likes' => $this->total_likes,
            'ttl_views' => $this->total_views,
            'ttl_comments' => $this->total_comments,
            'twitter' => $this->twitter,
            'onlyfans' => $this->onlyfans,
            'instagram' => $this->instagram,
            'about'=>$this->about,
            'post_id' => $this->post_id,
            'model_id' => $this->model_id,
            'model_name' => $this->model_name
        ];

        return json_encode($model_data);
    }
    function db_to_model($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->image = $data['image'];
      //  $this->slug = str_replace(' ', '-',trim($data['name']));
        $this->slug = sanitize_title($data['name']);
        $this->total_followers = $data['ttl_follows'];
        $this->total_likes = $data['ttl_likes'];
        $this->total_views = $data['ttl_views'];
        $this->total_comments = $data['ttl_comments'];
    }

    function tag_description_to_data_build($js_data)
    {
        if(strlen($js_data['image'])>5)
        {
            $this->image = $js_data['image'];
        }
                if(strlen($js_data['twitter'])>5)
        {
            $this->twitter = $js_data['twitter'];
        }
                if(strlen($js_data['onlyfans'])>5)
        {
            $this->onlyfans = $js_data['onlyfans'];
        }
                if(strlen($js_data['instagram'])>5)
        {
            $this->instagram = $js_data['instagram'];
        }
                if(strlen($js_data['about'])>5)
        {
            $this->about = $js_data['about'];
        }
    }


    function get_socal_links_html()
    {
        $para = '';
        $insta = '<a rel="nofollow" target="_blank" class="bg-purple-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-purple-400">Instagram</a>';
        if($this->instagram != '')
        {
            $insta = '<a href="'.$this->instagram.'" rel="nofollow" target="_blank" class="bg-purple-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-purple-400">Instagram</a>';
        }
        $onlyf = '<a rel="nofollow" target="_blank" class="bg-blue-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-blue-400">OnlyFans</a>';
        if($this->onlyfans != '')
        {
            $onlyf = '<a href="'.$this->onlyfans.'" rel="nofollow" target="_blank" class="bg-blue-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-blue-400">OnlyFans</a>';
        }
        $twi = '<a rel="nofollow" target="_blank" class="bg-indigo-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-indigo-400">Twitter</a>';
        if($this->twitter != '')
        {
          $twi = '<a href="'.$this->twitter.'" rel="nofollow" target="_blank" class="bg-indigo-300 shadow-sm p-0 pink-500 px-3 rounded-md text-white hover:text-white hover:bg-indigo-400">Twitter</a>';
        }

        return <<<sdfkgsdiofgsdifdsgif
 <p class="lg:text-left mb-2 text-center  dark:text-gray-100">
 {$onlyf}
 {$insta}
 {$twi}
</p>
sdfkgsdiofgsdifdsgif;

    }

    function update_save_database()
    {
        global $wpdb;
        $wpdb->get_results("UPDATE `fape_model` SET `post_id`='{$this->post_id}',`name`='{$this->name}',`ttl_likes`='{$this->total_likes}',
`ttl_follows`='{$this->total_followers}',`ttl_views`='{$this->total_views}',`ttl_comments`='{$this->total_comments}',`image`='{$this->image}' WHERE id = '{$this->id}'");
return 1;
    }

}

class fapello_by_tagdata{

    public $id = '';
    public $name = '';
    public $image = '';
    public $twitter = '';
    public $onlyfans = '';
    public $instagram = '';
    public $about = '';

    function get_json()
    {


    }

}