<?php

global $media_id;
global $wpdb;
global $is_user;
global $user_id;

$media_data = [];
$model_data = [];
$model_id = 0;

if(isset($media_id))
{
global $fapello_ajax;
$media_data = $fapello_ajax->get_media_byid($media_id)[0];
$model_id = $media_data->model_id;
//echo $model_id;
$model_data = $fapello_ajax->get_model_by_id($model_id)[0];

//print_r($media_data);
//print_r($model_data);

}


$HTTP = site_url();
$the_id = get_the_ID();

//$post_thumb =  the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full', 'title' => 'Feature image']);
$img = "data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20364%20205'%3E%3C/svg%3E";
$img ="http://localhost/wp/wp-content/uploads/2021/03/michael-dam-mEZ3PoFGs_k-unsplash-scaled-1.jpg";

$msulg = sanitize_title($media_data->model_name);
if(isset($model_data->image))
{
    $img = $model_data->image;
}




$model_url = "{$HTTP}/Model/{$msulg}";
$model_name = $media_data->model_name;


$image_html = '';
if($media_data->type == 'image')
{
    $image_html =<<<dgfhfdgfdiuogfdghfdkgfd
<a href="{$media_data->link}" target="_blank" class="uk-align-center">  
<img src="{$media_data->link}" alt="{$model_name} Nude Leaked OnlyFans Photo #{$media_id}">
</a>
dgfhfdgfdiuogfdghfdkgfd;
}else{
    $image_html =<<<dgfhfdgfdiuogfdghfdkgfd
<video preload="none" width="400" controls="controls" preload="metadata" class="uk-align-center">
  <source src="{$media_data->link}#t=0.5" type="video/mp4">
</video>
dgfhfdgfdiuogfdghfdkgfd;
}


$next_media_url = '';
$previous_media_url = '';
if(1)
{
    $xq= "SELECT * FROM fape_media where model_id = '{$model_id}' and id < '{$media_id}' limit 1";
    $result =  $wpdb->get_row($xq);
    if(isset($result->id))
    {
        $previous_media_url = $model_url.'/'. $result->id;
    }
    $xq= "SELECT * FROM fape_media where model_id = '{$model_id}' and id > '{$media_id}' limit 1";
    $result =  $wpdb->get_row($xq);
    if(isset($result->id))
    {
        $next_media_url = $model_url.'/'. $result->id;
    }

}

$comments_area = '';
$bgcolor = '';

if($is_user)
{
    // load user comments
    if($fapello_ajax->is_liked_media($media_id))
    {
        $bgcolor = 'background-color: red;';
    }
    if(isset($_REQUEST['act']))
    {
        if($_REQUEST['act'] =='subs')
        {
            $fapello_ajax->add_likes($media_id,1);

        }
    }

    if(isset($_POST['message']))
    {
        $message_text = $_POST['message'];
       $user_id = get_current_user_id();
        $fapello_ajax->add_comment($media_id,$message_text,$user_id);
    }


    $comnt_str = $fapello_ajax->a_get_comments_by_media_id($media_id);

    $comments_area = <<<sdldkhfdskuufgdsakiufgdsifgiuds
<div class="p-3 border-b dark:border-gray-700 uk-text-center">
<div class="border-t pt-4 space-y-4 dark:border-gray-600">
		{$comnt_str}
</div>
<form method="post" action="">
		<div class="uk-margin">
				<textarea name="message" class="uk-textarea" rows="1" placeholder="Say Something about this media content" style="min-height: 4rem;"></textarea>
		</div>
		<div>
				<button type="submit" class="bg-blue-400 font-bold hover:bg-blue-600 hover:text-white inline-block items-center px-4 py-2 rounded shado text-white">Post</button>
		</div>	
</form>
</div>


sdldkhfdskuufgdsakiufgdsifgiuds;


}else{
    $comments_area = <<<sldfhdsfghdskfgdsyfgds
<div class="p-3 border-b dark:border-gray-700 uk-text-center">
		<div class="uk-margin">
				<textarea name="message" class="uk-textarea" rows="1" placeholder="You must be logged in to post a comment. Please, tap here for registration." style="min-height: 4rem;" onclick="location.href='{$HTTP}/wp-admin/'"></textarea>
		</div>
		<div>
				<button type="submit" class="bg-blue-400 font-bold hover:bg-blue-600 hover:text-white inline-block items-center px-4 py-2 rounded shado text-white" onclick="location.href='{$HTTP}/wp-admin/'">Post</button>
		</div>												
</div>
sldfhdsfghdskfgdsyfgds;

}




echo $model_page_template = <<<sadlfhdfsfoireifpregfiuusdfiguu
<script>
    ajax_data.pagename = 'media-single';
    ajax_data.model_id = {$model_id};
    ajax_data.media_id = {$media_id};
    
</script>
				<div class="mx-auto">
					<div class="bg-white shadow rounded-md dark:bg-gray-900 -mx-2 lg:mx-0">
						<!-- post header-->
						<div class="flex justify-between items-center p-5 border-b">
							<div class="flex flex-1 items-center space-x-4">
								<a href="{$model_url}">
								   <img src="{$img}" class="bg-gray-200 border border-white rounded-full w-10 h-10">
								</a>
								<a href="{$model_url}" class="block capitalize font-semibold dark:text-gray-100 text-lg"> {$model_name}</a> 

								<span class="text-gray-400 text-base ml-2">Photo #{$media_id}</span> 
							</div>
						  <div>
								<a href="{$model_url}/{$media_id}/"> <i class="uil-share-alt text-2xl hover:bg-gray-200 rounded-full p-2 transition -mr-1 dark:hover:bg-gray-700"></i> </a>
						  </div>
						</div>
						<div class="flex justify-between items-center">
							{$image_html}
						</div>
						<div class="flex space-x-5 py-2 px-5 border-b">
							 <div class="flex-1">
								 <p class="text-base"> {$model_name} </p>
							 </div>
						</div>				
						<div class="flex space-x-5 p-5 border-b">
							<a href="{$previous_media_url}" style="text-align: center; display: block;" class="bg-blue-400 flex font-bold  hover:bg-blue-600 hover:text-white inline-block items-center lg:block max-h-10 mr-4 px-4 py-2 rounded shado text-white uk-width-1-2">Previous</a>
							<a href="{$next_media_url}" style="text-align: center; display: block;" class="bg-pink-500 flex font-bold  hover:bg-pink-600 hover:text-white inline-block items-center lg:block max-h-10 mr-4 px-4 py-2 rounded shado text-white uk-width-1-2">Next</a>
						</div>
						<div class="py-3 px-4 space-y-3"> 
								<div class="flex space-x-4 lg:font-bold">
								<a href="{$model_url}/{$media_id}?act=subs" class="flex items-center space-x-2" style="{$bgcolor}"><div class="p-2 rounded-full text-black" id="like_1024385"><svg aria-label="Like" color="#000000" fill="#000000" height="24" role="img" viewBox="0 0 48 48" width="24"><path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg></div></a>
								<a href="{$model_url}/{$media_id}" class="flex items-center space-x-2">
									<div class="p-2 rounded-full text-black">
										<svg aria-label="Comment" class="_8-yf5 " viewBox="0 0 48 48" style="width:24px; height:24px"><path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"></path></svg>
									</div>
								</a>
								</div>
								<div class="flex items-center space-x-3"> 
									<div class="flex items-center" id="last_likes_1024385">																		
									</div>
									<div class="flex items-center" id="count_likes_1024385">
									</div>
								</div>
						</div>
{$comments_area}
					</div>
				</div>
<div id="content">

</div>
sadlfhdfsfoireifpregfiuusdfiguu;
