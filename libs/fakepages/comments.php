<?php

global $fapello_ajax;

$fap_comments = $fapello_ajax->a_get_recent_comments();


echo <<<sdhifoidsifodisfhdsofdshofidsgfdsiou


<script>
    ajax_data.pagename = 'comments';
</script>

<div>
{$fap_comments}
</div>

sdhifoidsifodisfhdsofdshofidsgfdsiou;
