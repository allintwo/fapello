<?php

global $media_id;
global $fapello_ajax;
global $vpage;
$media_data = [];
$model_data = [];
$model_id = 0;



if(isset($media_id))
{

$media_data = $fapello_ajax->get_media_byid($media_id)[0];
$model_id = $media_data->id;

$model_data = $fapello_ajax->get_model_by_id($model_id)[0];

print_r($media_data);
print_r($model_data);

}


$HTTP = site_url();
$the_id = get_the_ID();

//$post_thumb =  the_post_thumbnail('post-thumbnail', ['class' => 'img-responsive responsive--full', 'title' => 'Feature image']);
$img = "data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20364%20205'%3E%3C/svg%3E";
$img ="http://localhost/wp/wp-content/uploads/2021/03/michael-dam-mEZ3PoFGs_k-unsplash-scaled-1.jpg";

$top10models = '';

$pagename = 'model-list';

$top10models = $fapello_ajax->a_get_top10_models(0);
if ($vpage =='random')
{

    $top10models = $fapello_ajax->a_get_top10_models(0,'rand()');
}



$somemodellist = '';

if($vpage =='videos')
{
    $somemodellist = $fapello_ajax->a_get_videos_list(0);
}else{
    $somemodellist = $fapello_ajax->a_get_model_media_list($vpage);
}


echo $model_page_template = <<<sadlfhdfsfoireifpregfiuusdfiguu
<script>
    ajax_data.pagename = '{$vpage}';
   // ajax_data.model_id = {$model_id};
  //  ajax_data.media_id = {$media_id};
    
</script>

<div>
				<div class="flex justify-between items-baseline uk-visible@s">
					<h1 class="font-extrabold leading-none mb-6 mt-8 lg:text-2xl text-lg text-gray-900 tracking-tight"> Popular on This Week</h1>
				</div>
				<div class="relative uk-slider" uk-slider="finite: true">
					<div class="uk-slider-container pb-3 -ml-3">
						<ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m uk-grid-small" style="transform: translate3d(0px, 0px, 0px);">
						{$top10models}
						</ul>

						<a class="uk-position-center-left uk-position-small p-3.5 bg-white rounded-full w-10 h-10 flex justify-center items-center -mx-4 mb-6 shadow-md dark:bg-gray-800 dark:text-white uk-icon uk-slidenav-previous uk-slidenav uk-invisible" href="#" uk-slidenav-previous="" uk-slider-item="previous"><svg width="14px" height="24px" viewBox="0 0 14 24" xmlns="http://www.w3.org/2000/svg" data-svg="slidenav-previous"><polyline fill="none" stroke="#000" stroke-width="1.4" points="12.775,1 1.225,12 12.775,23 "></polyline></svg></a>
						<a class="uk-position-center-right uk-positsion-small p-3.5 bg-white rounded-full w-10 h-10 flex justify-center items-center -mx-4 shadow-md dark:bg-gray-800 dark:text-white uk-icon uk-slidenav-next uk-slidenav" href="#" uk-slidenav-next="" uk-slider-item="next"><svg width="14px" height="24px" viewBox="0 0 14 24" xmlns="http://www.w3.org/2000/svg" data-svg="slidenav-next"><polyline fill="none" stroke="#000" stroke-width="1.4" points="1.225,23 12.775,12 1.225,1 "></polyline></svg></a>

					</div>

				</div>

				<h1 class="font-extrabold leading-none mb-6 mt-8 lg:text-2xl text-lg text-gray-900 tracking-tight"> {$vpage} in the Last Hour by Views </h1>
				<div id="content">
				<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">
            	</div>
					<div class="somemodellist">
						{$somemodellist}
					</div>
			

				<div class="my-3 grid lg:grid-cols-4 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">
				</div>
				</div>

</div>

sadlfhdfsfoireifpregfiuusdfiguu;
