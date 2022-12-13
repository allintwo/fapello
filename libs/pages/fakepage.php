<?php
global $media_id;
global $fapello_ajax;
global $vpage;

$title = 'Demo page';





$page_link =   get_template_directory() . "/libs/fakepages/{$vpage}.php";

if(is_file($page_link))
{
include_once $page_link;
    add_filter('the_title','somexyz_titlecallback',0);
    function somexyz_titlecallback($data){
        global $vpage;
        // where $data would be string(#) "current title"
        // Example:
        // (you would want to change $post->ID to however you are getting the book order #,
        // but you can see how it works this way with global $post;)
        return $vpage .'';
    }

}else{
    echo <<<sdkjfkgdskufdsgifdsgifdsiufdsiufuids

<script>
    ajax_data.pagename = '{$vpage}';
</script>
<div>
    <div class="flex justify-between items-baseline uk-visible@s">
                        <h1 class="font-extrabold leading-none mb-6 mt-8 lg:text-2xl text-lg text-gray-900 tracking-tight">{$title}</h1>
    </div>
</div>


sdkjfkgdskufdsgifdsgifdsiufdsiufuids;
}


