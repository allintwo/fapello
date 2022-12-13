<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Understrap
 */

$HTTP = site_url();

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

include_once 'install/callme.php'; // installing command
add_action("after_switch_theme", "fapello_create_extra_table");

include_once 'libs/addon/yoast.php'; // installing command


if(is_admin())
{
    include_once 'libs/admin_menu.php';
    include_once 'libs/admin_tags.php';
}


$userid = 0;
$is_loggedin = 0;
if(is_user_logged_in())
{
    $is_loggedin =1;
    $userid = get_current_user_id();
}


require_once 'libs/fapello.php';
$fapello = new fapello($wpdb,$userid);
global $fapello_ajax;

$fapello_ajax = new fapello_ajax($wpdb,$userid);
// $fapello_ajax->get_top_liked_models();

require_once 'ajax/ajax.php';
require_once 'ajax/cronjobs.php';

// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = array(
  //  '/virtual_page.php',                      // Load create page
    '/vpage.php',                      // Load create page
    '/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	// '/block-editor.php',                    // Load Block Editor functions.
	'/deprecated.php',                      // Load deprecated functions.

);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$understrap_includes[] = '/jetpack.php';
}



// Include files.
foreach ( $understrap_includes as $file ) {
	require_once get_theme_file_path( $understrap_inc_dir . $file );
}

include_once 'libs/custom-texa.php';

function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}


function ImageVideUrlFinder($strx)
{
    $themedir = get_template_directory();
    include_once $themedir . '/libs/simple_html_dom.php';
    $dom = str_get_html($strx);

    $images = [];
    if(1)
    {
        $imgs = $dom->find('img');
        foreach ($imgs as $img)
        {
            if(strlen($img->src)>5)
            {
                $images[] =  $img->attr['src'];
            }
        }
    }
    $videos = [];
    if(1)
    {
        $vids = $dom->find('source');
        foreach ($vids as  $vid)
        {
            if(strlen($vid->src) > 10)
            {
                $videos[] = [$vid->src];
            }
        }
    }
    return ['images' => $images,'videos' => $videos];
}


function number_shorten($number, $precision = 3, $divisors = null) {

    // Setup default $divisors if not provided
    if (!isset($divisors)) {
        $divisors = array(
            pow(1000, 0) => '', // 1000^0 == 1
            pow(1000, 1) => 'K', // Thousand
            pow(1000, 2) => 'M', // Million
            pow(1000, 3) => 'B', // Billion
            pow(1000, 4) => 'T', // Trillion
            pow(1000, 5) => 'Qa', // Quadrillion
            pow(1000, 6) => 'Qi', // Quintillion
        );
    }

    // Loop through each $divisor and find the
    // lowest amount that matches
    foreach ($divisors as $divisor => $shorthand) {
        if (abs($number) < ($divisor * 1000)) {
            // We found a match!
            break;
        }
    }

    // We found our match, or there were no matches.
    // Either way, use the last defined value for $divisor.
    return number_format($number / $divisor, $precision) . $shorthand;
}