<?php
$debugs = [];

global $fapello_ajax;
$fapello_model = new fapello_model();

$the_title = get_the_title();
$the_id = get_the_ID();

$debugs['the_id'] = $the_id;

 // echo $the_id; return; //  6178
$term_obj_list = get_the_terms( $the_id, 'Model' );
$debugs['term_objs'] = $term_obj_list;

$models_htmls = '';
if (is_array($term_obj_list)){
    foreach ($term_obj_list as $term)
    {
      //  $t_description = $term->description;
       // $fapello_model->db_to_model($t_description);

        $models_htmls .= $fapello_ajax->a_get_model_profile_by_term_data($term,'',0);
    }
}



// $posted_on = understrap_posted_on();
$post_url = esc_url( get_permalink() );
$post_content = strip_tags( get_the_content(),'<p><b><i><strong>');
$post_categorys = get_the_category();
$post_tax = get_taxonomies();
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





$HTTP = site_url();



$debugx = print_r($debugs,1);

$post_more_data = '';
if(1)
{
    $post_more_data = <<<sfhsdofhdsifudsgfiudsgfiu
<script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>
<div id="accordion-collapse" data-accordion="collapse">
  <h2 id="accordion-collapse-heading-1">
    <button type="button" class="flex justify-between items-center p-5 w-full font-medium text-left text-gray-500 rounded-t-xl border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
      <span>Post Content </span>
      <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
  </h2>
  <div id="accordion-collapse-body-1" class="hidden" aria-labelledby="accordion-collapse-heading-1">
    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
        <h1>{$the_title}</h1>
        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">
        {$post_content}
        </div>
    </div>
  </div>
  <h2 id="accordion-collapse-heading-2">
    <button type="button" class="flex justify-between items-center p-5 w-full font-medium text-left text-gray-500 border border-b-0 border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-collapse-body-2" aria-expanded="false" aria-controls="accordion-collapse-body-2">
      <span>More Info</span>
      <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
  </h2>
  <div id="accordion-collapse-body-2" class="hidden" aria-labelledby="accordion-collapse-heading-2">
    <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700">
    
    </div>
  </div>
</div>
<div>

</div>
sfhsdofhdsifudsgfiudsgfiu;


    $post_more_data = "<div class='post-content'>{$post_content}</div>";
}




$model_contents = <<<sdfhdslfgdsfiidsgf
        <div>
            <a href="#">
                <div class="max-w-full lg:h-64 h-40 rounded-md relative overflow-hidden uk-transition-toggle" tabindex="0">
                    <img src="{$img}" class="w-full h-full absolute object-cover inset-0">
                </div>
            </a>
        </div>
sdfhdslfgdsfiidsgf;

// $model_contents = $fapello_ajax->a_get_medias_by($model_id);


$social_links = $fapello_model->get_socal_links_html();



echo $model_page_template = <<<sadlfhdfsfoireifpregfiuusdfiguu
<script>
    ajax_data.pagename = 'tag-model';
</script>

{$models_htmls}
<hr class="uk-divider-icon">

<div>
<!-- dd -->
{$post_more_data}
<!-- xx -->
</div>


    <div id="content" class="my-6 grid lg:grid-cols-4 grid-cols-2 gap-1.5 hover:text-yellow-700 uk-link-reset">
    <!-- ajax content will added here -->
    {$model_contents}
    </div>
<!-- xyz
{$debugx}
-->

sadlfhdfsfoireifpregfiuusdfiguu;
