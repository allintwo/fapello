<?php
global $fapello_ajax;
global $post_type;
// print_r($post_type);

$js_strx = <<<dfghsdfkjdsfhgdskj
 ajax_data.pagename = 'tag-model';
dfghsdfkjdsfhgdskj;




echo $fapello_ajax->a_get_model_profile_by_term_data($post_type);