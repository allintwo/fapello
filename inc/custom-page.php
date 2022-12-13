<?php


// create custom virtual page

function custom_rewrite_rule() {
    add_rewrite_rule('^nutrition/?([^/]*)/?','index.php?page_id=12&food=$matches[1]','top');
    echo 'nutrition';
}
add_action('init', 'custom_rewrite_rule', 10, 0);