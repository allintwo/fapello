<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/*
$post_id = the_ID();
$post_class = post_class();
the_title(
    sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
    '</a></h2>'
);
get_post_type() == 'post';
understrap_posted_on();
get_the_post_thumbnail( $post->ID, 'large' );
the_excerpt();
understrap_link_pages();
understrap_entry_footer();
*/

$the_title = get_the_title();
$the_id = get_the_ID();
// $posted_on = understrap_posted_on();
$post_url = esc_url( get_permalink() );
$post_content = get_the_content();
$post_categorys = get_the_category();
$firstCategory = '';
$firstCategory_url = '';
foreach ($post_categorys as $post_category)
{
    $firstCategory = $post_categorys[0]->cat_name;
    $firstCategory_url = esc_url( get_category_link( $post_categorys[0]->term_id ) );

    break;
}
//$post_thumb =  the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full', 'title' => 'Feature image']);
$img = "data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20364%20205'%3E%3C/svg%3E";
if (has_post_thumbnail( $the_id ) ):
    $img = get_the_post_thumbnail_url($the_id, 'post-thumbnail');
endif;
$fimage = $img;

// get model by post id
$imgs = [];
$imgs[0] = $img;
$imgs[1] = $img;
$imgs[2] = $img;

$model_name = $the_title;
$total_media = 0;
if(1)
{
    global $fapello_ajax;
    $model_data = $fapello_ajax->get_model_data_by_post_id($the_id);
    if(isset($model_data->image))
    {
        $img = $fapello_ajax->xzy_fix_image_url( $model_data->image);
        $fimage = $img;

        $imgs[0] = $img;
        $imgs[1] = $img;
        $imgs[2] = $img;

    }
    if(isset($model_data->id))
    {
        $model_name  = $model_data->name;
        $total_media = $fapello_ajax->a_get_total_media_count_by_model_id($model_data->id);
        $image_urls = $fapello_ajax->get_new_media_url_by_model_id($model_data->id,7);
        $i = 0;
        $tlen = count($image_urls);
       // $image_urls = array_reverse($image_urls);
        if($tlen > 0)
        {
            $fimage = $image_urls[$tlen -1]['url'];
        }
        foreach ($image_urls as $image_url)
        {
            $imgs[$i] = $image_url['url'];
            $i++;
        }
    }
}

$comment_area = '';


global $is_user;

if($is_user)
{

}else{
    $comment_area = <<<sdflldshfdsghfkdsgfkdsagfl

    <div class="py-3 px-4 space-y-3">

        <div class="flex space-x-4 lg:font-bold">
            <a href="/signup/" class="flex items-center space-x-2"><div class="p-2 rounded-full text-black" id="like_44619"><svg aria-label="Like" color="#000000" fill="#000000" height="24" role="img" viewBox="0 0 48 48" width="24"><path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg></div></a>

            <a href="{$post_url}" class="flex items-center space-x-2">
                <div class="p-2 rounded-full text-black">
                    <svg aria-label="Comment" class="_8-yf5 " viewBox="0 0 48 48" style="width:24px; height:24px"><path clip-rule="evenodd" d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z" fill-rule="evenodd"></path></svg>
                </div>
            </a>

            <a href="/signup/" class="bg-pink-500 flex font-bold hover:bg-pink-600 hover:text-white inline-block items-center px-4 py-2 rounded shado text-white follow_button" id="follow_12714" model_id="12714">Follow Free</a>								</div>
        <div class="flex items-center space-x-3">

            <div class="flex items-center" id="last_likes_44619">

            </div>

            <div class="flex items-center" id="count_likes_44619">
            </div>
        </div>

    </div>


    <div class="p-3 border-b dark:border-gray-700 uk-text-center">
        <div class="uk-margin">
            <textarea name="message" class="uk-textarea" rows="1" placeholder="You must be logged in to post a comment. Please, tap here for registration." style="min-height: 4rem;" onclick="location.href='/wp-admin'"></textarea>
        </div>

        <div>
            <button type="submit" class="bg-blue-400 font-bold hover:bg-blue-600 hover:text-white inline-block items-center px-4 py-2 rounded shado text-white" onclick="location.href='/wp-admin'">Post</button>
        </div>
    </div>

sdflldshfdsghfkdsgfkdsagfl;

}




echo <<<sdkfhdsiufgdsuifgdsiofdsgfiodsgofids
<div class="bg-white shadow rounded-md dark:bg-gray-900 -mx-2 lg:mx-0" id="post-{$the_id}">

    <div class="flex justify-between items-center px-4 py-3">
        <div class="flex flex-1 items-center space-x-4">
            <a href="{$post_url}">
                <div class="bg-gradient-to-tr from-yellow-600 to-pink-600 p-0.5 rounded-full">
                    <img src="{$img}" class="bg-gray-200 border border-white rounded-full w-8 h-8">
                </div>
            </a>
            <a href="{$post_url}"><span class="block capitalize font-semibold dark:text-gray-100"> {$the_title} </span></a>
        </div>
        <div>

            <a href="#"> <i class="uil-share-alt text-2xl hover:bg-gray-200 rounded-full p-2 transition -mr-1 dark:hover:bg-gray-700"></i> </a>

        </div>
    </div>


    <div>
        <div class="grid grid-cols-2 gap-2 p-2">

            <a href="{$post_url}" class="col-span-2">
                <img src="{$fimage}" alt="{$the_title}-1" class="rounded-md w-full lg:h-76 object-cover img_feed">
            </a>
            <a href="{$post_url}">
                <img src="{$imgs[1]}" alt="{$the_title}-2" class="rounded-md w-full h-full img_feed">
            </a>
            <a href="{$post_url}" class="relative">
                <img src="{$imgs[2]}" alt="{$the_title}-3" class="rounded-md w-full h-full img_feed">
                <div class="absolute bg-gray-900 bg-opacity-30 flex justify-center items-center text-white rounded-md inset-0 text-2xl">
                    + {$total_media} photos
                </div>

            </a>

        </div>
    </div>


    <div class="p-3 border-b dark:border-gray-700 uk-text-center">

        <a href="{$post_url}" class="bg-blue-500 flex font-bold hover:bg-blue-600 hover:text-white inline-block items-center px-4 py-2 rounded shado text-white" style="display: block;">See all content of {$model_name}</a>

    </div>
{$comment_area}
</div>
sdkfhdsiufgdsuifgdsiofdsgfiodsgofids;

?>



