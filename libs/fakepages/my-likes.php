<?php

global $fapello_ajax;

$mylikes_html = '';
if(1)
{
    $mylikes_html = $fapello_ajax->a_get_media_likes_by_user(get_current_user_id());
}


echo <<<sdkjdjdhfdsijldfhdsiufgusdfiupdsuifuds

<script>
    ajax_data.pagename = 'my-likes';
</script>

<div>




<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">
{$mylikes_html}
</div>
</div>


sdkjdjdhfdsijldfhdsiufgusdfiupdsuifuds;
