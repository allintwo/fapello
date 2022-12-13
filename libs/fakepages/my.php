<?php


global $fapello_ajax;

$subs_html = '';
if(1)
{
    $subs_html = $fapello_ajax->a_get_subs_by_userid(0,15,1);
}

echo <<<wseljkfhdsdkjfgdskfgdsiufdsg
<script>
    ajax_data.pagename = 'my';
</script>

<div>

<div class="mt-6 grid lg:grid-cols-3 grid-cols-2 gap-3 hover:text-yellow-700 uk-link-reset">
{$subs_html}
</div>
</div>

wseljkfhdsdkjfgdskfgdsiufdsg;
