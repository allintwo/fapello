<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $is_user;
global $fapello_ajax;
$is_user = 0;
$user_id = 0;

if(is_user_logged_in())
{
    $is_user =1;
    $user_id = get_current_user_id();
    // echo $user_id;
}

$HTTP = site_url();
$THTTP = get_template_directory_uri();


$bootstrap_version = get_theme_mod( 'understrap_bootstrap_version', 'bootstrap4' );
$navbar_type       = get_theme_mod( 'understrap_navbar_type', 'collapse' );
$theme_dir = get_template_directory_uri();
$max_pages = 50;

function get_custom_logo_url()
{
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    return $image[0];
}

$site_logo = get_custom_logo_url();
if($site_logo == '')
{
     $site_logo = "{$THTTP}/assets/images/logo-light.png";
}else{

}

$side_link_1 = '';
$side_link_2 = '';

$sidebar_user_html = '';
if($is_user)
{

    $avatar_url = get_avatar_url($user_id);

$side_link_2 = <<<sdlfhdskfgdsjkfdsjfdsf

						<a href="#" aria-expanded="false" class="">
							<img src="{$avatar_url}" class="header-avatar" alt="">
						</a>
						<div uk-drop="mode: click;offset:9" class="header_dropdown profile_dropdown border-t uk-drop" style="left: 1137px; top: 7.5px;">
							<ul>
								<li><a href="{$HTTP}"> Home </a> </li>
								<li><a href="{$HTTP}/my/"> My Feed </a> </li>
								<li><a href="{$HTTP}/subs/"> My Subscriptions </a> </li>
								<li><a href="{$HTTP}/my-likes/"> My Likes </a> </li>
								<li><hr class="my-2"></li>									
								<li><a href="{$HTTP}/wp-admin/profile.php"> Profile </a></li>
								<li><a href="{$HTTP}/login/logout/"> Log Out</a></li>
							</ul>
						</div>
					

sdlfhdskfgdsjkfdsjfdsf;


    $side_link_1 = <<<sddlkfhdskfggdsfiudsiu
				
					<li>
						<a href="{$HTTP}/my/"> 
						
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
							</svg> 
							<span> My Feed </span> </a> 
					</li>


					<li>
						<a href="{$HTTP}/subs/"> 
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68A1,1,0,0,0,6.9,21.44L12,18.77l5.1,2.67a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.88l.72,4.2-3.76-2a1.06,1.06,0,0,0-.94,0l-3.76,2,.72-4.2a1,1,0,0,0-.29-.88l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z"></path>
							</svg> 
							<span> My Subscriptions </span> </a> 
					</li>
					<li>
						<a href="{$HTTP}/search/">
							<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></path><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"></path></svg>
							<span> Search </span> </a>
					</li>
					<li>
						<a href="{$HTTP}/my-likes/">
							<svg width="21px" height="20px" viewBox="0 0 21 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<g id="Dribbble-Light-Preview" transform="translate(-139.000000, -320.000000)" fill="#000000">
										<g id="icons" transform="translate(56.000000, 160.000000)">
											<path d="M98.7860271,170.404 C97.7402268,171.416 97.2624767,172.872 97.5102768,174.301 L97.9691269,176.957 L95.5646263,175.703 C94.9304261,175.372 94.216426,175.197 93.5003258,175.197 C92.7831756,175.197 92.0691754,175.372 91.4349753,175.703 L89.0304747,176.957 L89.4903748,174.301 C89.7371249,172.872 89.2593748,171.416 88.2135745,170.404 L86.268974,168.523 L88.9569747,168.135 C90.402825,167.927 91.6512753,167.027 92.2980755,165.727 L93.5003258,163.31 L94.7025761,165.727 C95.3483262,167.027 96.5978265,167.927 98.0426269,168.135 L100.731678,168.523 L98.7860271,170.404 Z M103.423878,169.433 C104.551578,168.342 103.929978,166.441 102.370728,166.216 L98.408027,165.645 C97.7885268,165.556 97.2530267,165.17 96.9768766,164.613 L95.2044762,161.051 C94.8558761,160.35 94.1775759,160 93.5003258,160 C92.8220256,160 92.1437255,160.35 91.7951254,161.051 L90.0237749,164.613 C89.7465749,165.17 89.2110747,165.556 88.5926246,165.645 L84.6299236,166.216 C83.0706733,166.441 82.4480231,168.342 83.5757234,169.433 L86.4432741,172.206 C86.8916242,172.639 87.0963742,173.263 86.9903242,173.875 L86.313074,177.791 C86.102024,179.01 87.0785242,180 88.1862745,180 C88.4781746,180 88.7805746,179.931 89.0714247,179.779 L92.6151756,177.93 C92.8923756,177.786 93.1958257,177.713 93.5003258,177.713 C93.8037759,177.713 94.1072259,177.786 94.384426,177.93 L97.9292269,179.779 C98.2200769,179.931 98.521427,180 98.8133271,180 C99.9210773,180 100.897578,179.01 100.686528,177.791 L100.010327,173.875 C99.9042773,173.263 100.107977,172.639 100.556327,172.206 L103.423878,169.433 Z" id="star_favorite-[#1498]"></path>
										</g>
									</g>
								</g>
							</svg>
							<span> My Likes </span> <span class="nav-tag" style="width: 30px; line-height: 9px; padding: 4px;"> new</span> </a>
					</li>
sddlkfhdskfggdsfiudsiu;


}else{

    $side_link_2 = <<<sdffghdskjfdsgfidsgfiudsi
  <a href="{$HTTP}/wp-admin/" class="bg-blue-400 flex font-bold hover:bg-blue-600 hover:text-white inline-block items-center lg:block max-h-10 mr-2 px-2 py-2 rounded shado text-white">Log In</a>
                        <a href="{$HTTP}/wp-admin/" class="bg-pink-500 flex font-bold hover:bg-pink-600 hover:text-white inline-block items-center lg:block max-h-10 mr-2 px-2 py-2 rounded shado text-white">Sign Up</a>
sdffghdskjfdsgfidsgfiudsi;


    $side_link_1 = <<<sdfhdskfudsgfuidsgfiuds
                    <li>
                        <a href="{$HTTP}/wp-admin/">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M344 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M256 304c-87 0-175.3 48-191.64 138.6C62.39 453.52 68.57 464 80 464h352c11.44 0 17.62-10.48 15.65-21.4C431.3 352 343 304 256 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                            <span> Log In </span> </a>
                    </li>

                    <li>
                        <a href="{$HTTP}/wp-admin/">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M376 144c-3.92 52.87-44 96-88 96s-84.15-43.12-88-96c-4-55 35-96 88-96s92 42 88 96z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M288 304c-87 0-175.3 48-191.64 138.6-2 10.92 4.21 21.4 15.65 21.4H464c11.44 0 17.62-10.48 15.65-21.4C463.3 352 375 304 288 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M88 176v112M144 232H32"/></svg>
                            <span> Sign Up </span> </a>
                    </li>

sdfhdskfudsgfuidsgfiuds;

}


$new_5_models = '';
if(1)
{
    $new_5_models = $fapello_ajax->a_get_last_3_model(5);
}


?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
		
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
            var ajax_url = "<?php echo esc_url( home_url() ); ?>/wp-admin/admin-ajax.php";
            var ajax_data = {
                action:'fapello_action',
                subaction:'none',
                actionvalue:0,
                pagename:'home',
                items:1,
                maxpage:5,
                page_number:0
            };
        </script>

        <script src="<?php echo $theme_dir; ?>/assets/js/jquery-3.3.1.min.js"></script>
        <script src="<?php echo $theme_dir; ?>/assets/js/js-jquery-3.3.1.min.js"></script>
        <script src="<?php echo $theme_dir; ?>/assets/js/js-tippy.all.min.js"></script>
        <script src="<?php echo $theme_dir; ?>/assets/js/js-uikit.js"></script>
        <?php wp_head(); ?>

        <link rel="stylesheet" href="<?php echo $theme_dir; ?>/assets/css/css-icons.css">
        <link rel="stylesheet" href="<?php echo $theme_dir; ?>/assets/css/css-uikit.css">
        <link rel="stylesheet" href="<?php echo $theme_dir; ?>/assets/css/css-style.css">
        <link rel="stylesheet" href="<?php echo $theme_dir; ?>/assets/css/css-tailwind-dark.css">

<!--  <link rel="stylesheet" href="<?php echo $theme_dir; ?>/assets/css/unused.min.css"> -->
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $theme_dir; ?>/assets/favicons/favicon-apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $theme_dir; ?>/assets/favicons/favicon-favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $theme_dir; ?>/assets/favicons/favicon-favicon-16x16.png">

        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <style>
            @media (min-width: 1024px) {
                header .header_inner {
                    max-width: 980px;
                }

                .pro-container {
                    max-width: 860px;
                }
            }

            div .showmore{
                margin:auto;
                width:4rem;
            }
            img .showmore{
                width:4rem;
            }

            .img_feed
            {
                object-fit: cover;
            }

            .video_player {
                width: 100% !important;
                height: auto !important;
            }
        </style>
		<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134867880-20"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134867880-20');
</script>

    </head>

    <body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
    <?php do_action( 'wp_body_open' ); ?>
    <?php
    echo <<<sdlfhriwpfghsidfghsdofhdsofhdsofhds

    <div id="wrapper">
        <div class="sidebar">
            <div class="sidebar_header border-b border-gray-200 from-gray-100 to-gray-50 bg-gradient-to-t  uk-visible@s">
                <a href="{$HTTP}">
                    <img src="{$site_logo}">
                    <img src="{$site_logo}" class="logo_inverse">
                </a>

            </div>
            <div class="border-b border-gray-20 flex justify-between items-center p-3 pl-5 relative uk-hidden@s">
                <h3 class="text-xl"> Navigation </h3>
                <span class="btn-mobile" uk-toggle="target: #wrapper ; cls: sidebar-active"></span>
            </div>
            <div class="sidebar_inner" data-simplebar>

                <ul>
                    <li>
                        <a href="{$HTTP}">
                            <i class="uil-home-alt"></i>
                            <span> Home </span> </a>
                    </li>
{$side_link_1}
                    <li>
                        <hr class="my-2">
                    </li>


                    <li>
                        <a href="{$HTTP}/trending/">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                            <span> Trending </span> </a>
                    </li>

                    <li>
                        <a href="{$HTTP}/videos/">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Play</title><path d="M112 111v290c0 17.44 17 28.52 31 20.16l247.9-148.37c12.12-7.25 12.12-26.33 0-33.58L143 90.84c-14-8.36-31 2.72-31 20.16z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/></svg>
                            <span> OnlyFans Videos </span> </a>
                    </li>

                    <li>
                        <a href="{$HTTP}/random/">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"><path fill="#444" d="M13 12h-2c-1 0-1.7-1.2-2.4-2.7-.3.7-.6 1.5-1 2.3C8.4 13 9.4 14 11 14h2v2l3-3-3-3v2zM5.4 6.6c.3-.7.6-1.5 1-2.2C5.6 3 4.5 2 3 2H0v2h3c1 0 1.7 1.2 2.4 2.6z"/><path fill="#444" d="M16 3l-3-3v2h-2C8.3 2 7.1 5 6 7.7 5.2 9.8 4.3 12 3 12H0v2h3c2.6 0 3.8-2.8 4.9-5.6C8.8 6.2 9.7 4 11 4h2v2l3-3z"/></svg>
                            <span> Random </span> </a>
                    </li>


                    <li>
                        <hr class="my-2">
                    </li>

                    <li>
                        <a href="{$HTTP}/top-likes/">
                            <svg aria-label="Like" color="#000000" fill="#000000" height="24" role="img" viewBox="0 0 48 48" width="24"><path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg>
                            <span> Top Models by Likes </span> </a>
                    </li>


                    <li>
                        <a href="{$HTTP}/top-followers/">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 611.998 611.998" style="enable-background:new 0 0 611.998 611.998;" xml:space="preserve">
							<g>
                                <g>
                                    <path d="M382.167,150.945c9.702,10.875,16.557,24.306,20.381,39.921c3.629,14.822,4.44,31.308,2.414,49.006
										c-0.751,6.546-1.861,13.96-3.479,21.802c12.532,12.135,28.95,19.839,50.296,19.838c59.22-0.005,80.529-59.3,86.105-108.006
										c6.872-60.004-21.498-105.163-86.105-105.163c-50.698,0-79.079,27.82-85.628,68.798
										C372.076,141.062,377.449,145.655,382.167,150.945z"/>
                                    <path d="M611.973,422.704c-0.645-18.899-2.861-37.887-6.161-56.495c-3.992-22.539-9.08-55.585-28.759-70.275
										c-11.38-8.491-26.117-11.278-39.143-16.398c-6.343-2.492-12.024-4.967-17.354-7.784c-17.995,19.734-41.459,30.055-68.782,30.057
										c-21.261,0-40.172-6.281-56.001-18.358c-3.644,11.272-8.522,22.623-15.044,32.994c5.728,3.449,11.923,6.204,19.451,9.162
										c3.332,1.31,6.99,2.506,10.864,3.771c10.472,3.422,22.339,7.301,32.994,15.255c25.329,18.907,31.564,54.336,36.117,80.207
										l0.49,2.792c2.355,13.266,4.084,26.299,5.197,38.961c20.215-2.071,40.327-5.61,60.047-9.774
										c15.941-3.365,31.774-7.471,47.109-13.003C605.247,439.397,612.476,437.343,611.973,422.704z"/>
                                    <path d="M160.216,281.511c21.345,0.002,37.762-7.703,50.295-19.838c-1.618-7.841-2.728-15.256-3.479-21.802
										c-2.026-17.697-1.214-34.184,2.414-49.006c3.823-15.614,10.679-29.046,20.381-39.921c4.718-5.291,10.09-9.884,16.014-13.805
										c-6.549-40.978-34.93-68.798-85.628-68.798c-64.606,0-92.977,45.16-86.106,105.163
										C79.687,222.212,100.996,281.507,160.216,281.511z"/>
                                    <path d="M167.957,344.634c10.655-7.954,22.524-11.833,32.994-15.255c3.875-1.265,7.531-2.461,10.864-3.771
										c7.528-2.957,13.725-5.711,19.451-9.162c-6.52-10.369-11.4-21.722-15.043-32.994c-15.829,12.077-34.741,18.358-56.001,18.358
										c-27.322-0.001-50.788-10.324-68.782-30.057c-5.329,2.817-11.012,5.291-17.354,7.784c-13.026,5.12-27.763,7.907-39.143,16.398
										c-19.678,14.691-24.767,47.735-28.759,70.275c-3.3,18.607-5.516,37.595-6.161,56.495c-0.502,14.64,6.726,16.693,18.974,21.112
										c15.334,5.531,31.17,9.637,47.109,13.003c19.72,4.165,39.833,7.704,60.047,9.774c1.112-12.662,2.841-25.693,5.197-38.961
										l0.49-2.792C136.394,398.971,142.628,363.541,167.957,344.634z"/>
                                    <path d="M470.351,429.405l-0.493-2.805c-4.258-24.197-10.091-57.334-32.191-73.832c-9.321-6.957-19.872-10.404-30.078-13.74
										c-4.019-1.313-7.812-2.554-11.427-3.974c-5.269-2.07-10.016-4.097-14.464-6.338c-18.684,24.932-44.58,38.059-75.383,38.062
										c-30.795,0-56.687-13.128-75.371-38.062c-4.449,2.243-9.196,4.269-14.467,6.34c-3.61,1.418-7.406,2.659-11.424,3.972
										c-10.207,3.335-20.761,6.784-30.079,13.74c-22.107,16.5-27.936,49.645-32.193,73.846l-0.493,2.795
										c-3.557,20.086-5.68,39.572-6.308,57.914c-0.737,21.519,12.62,26.316,24.403,30.55l1.269,0.457
										c14.17,5.112,30.021,9.492,48.457,13.388c37.646,7.946,68.197,11.74,96.138,11.938h0.072h0.072
										c27.946-0.199,58.495-3.992,96.135-11.938c18.439-3.894,34.289-8.274,48.453-13.387l1.268-0.456
										c11.786-4.233,25.147-9.029,24.41-30.553C476.03,468.931,473.906,449.447,470.351,429.405z"/>
                                    <path d="M221.005,243.009c5.577,48.709,26.883,108.009,86.103,108.006s80.529-59.297,86.106-108.006
										c6.871-60.002-21.503-105.16-86.106-105.16C242.515,137.847,214.123,183.002,221.005,243.009z"/>
                                </g>
                            </g>
							</svg>
                            <span> Top Models by Followers </span> </a>
                    </li>

                    <li>
                        <hr class="my-2">
                    </li>

                    <li>
                        <a href="{$HTTP}/comments/">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.61,19.19A7,7,0,0,0,17.87,8.62,8,8,0,1,0,3.68,14.91L2.29,16.29a1,1,0,0,0-.21,1.09A1,1,0,0,0,3,18H8.69A7,7,0,0,0,15,22h6a1,1,0,0,0,.92-.62,1,1,0,0,0-.21-1.09ZM8,15a6.63,6.63,0,0,0,.08,1H5.41l.35-.34a1,1,0,0,0,0-1.42A5.93,5.93,0,0,1,4,10a6,6,0,0,1,6-6,5.94,5.94,0,0,1,5.65,4c-.22,0-.43,0-.65,0A7,7,0,0,0,8,15ZM18.54,20l.05.05H15a5,5,0,1,1,3.54-1.46,1,1,0,0,0-.3.7A1,1,0,0,0,18.54,20Z"/></svg>
                            <span> Recent Comments </span> </a>
                    </li>

                    <li>
                        <hr class="my-2">
                    </li>
                    <li>
                        <a href="{$HTTP}/contacts/">
                            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Mail</title><rect x="48" y="96" width="416" height="320" rx="40" ry="40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M112 160l144 112 144-112"/></svg>
                            <span> Contacts / DMCA </span> </a>
                    </li>

                    <li>
                        <hr class="my-2">
                    </li>
                    {$new_5_models}
                 
                </ul>
            </div>
        </div>


        <div class="main_content">

            <header>
                <div class="header_inner">
                    <div class="left-side">
                        <!-- Logo -->
                        <div id="logo" class=" uk-hidden@s">
                            <a href="{$HTTP}">
                                <img src="{$site_logo}" alt="">
                                <img src="{$site_logo}" class="logo_inverse">
                            </a>
                        </div>

                        <div class="triger" uk-toggle="target: #wrapper ; cls: sidebar-active">
                            <i class="uil-bars"></i>
                        </div>


                    </div>

                    <div class="right-side lg:pr-4">

                        <a href="{$HTTP}/search/" class="bg-green-400 flex font-bold hover:bg-green-500 hover:text-white inline-block items-center lg:block max-h-10 mr-2 px-2 py-2 rounded shado text-white"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512" width="22" height="22"><path d="M221.09 64a157.09 157.09 0 10157.09 157.09A157.1 157.1 0 00221.09 64z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M338.29 338.29L448 448"/></svg></a>
                      
                      {$side_link_2}
                      
                    </div>
                </div>
            </header>
sdlfhriwpfghsidfghsdofhdsofhdsofhds;

