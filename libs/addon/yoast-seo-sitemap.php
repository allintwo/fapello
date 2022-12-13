<?php
/**
 *  Create a new custom yoast seo sitemap
 */

add_filter( 'wpseo_sitemap_index', 'ex_add_sitemap_custom_items' );
add_action( 'init', 'init_wpseo_do_sitemap_actions' );


// Add custom index
function ex_add_sitemap_custom_items(){
    global $wpseo_sitemaps;
    $date = $wpseo_sitemaps->get_last_modified('CUSTOM_POST_TYPE');

    $smp ='';

    $smp .= '<sitemap>' . "\n";
    $smp .= '<loc>' . site_url() .'/CUSTOM_KEY-sitemap.xml</loc>' . "\n";
    $smp .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
    $smp .= '</sitemap>' . "\n";


    return $smp;
}


function init_wpseo_do_sitemap_actions(){
    add_action( "wpseo_do_sitemap_CUSTOM_KEY", 'ex_generate_origin_combo_sitemap');
}




function ex_generate_origin_combo_sitemap(){


    global $wpdb;
    global $wp_query;
    global $wpseo_sitemaps;

    $post_type = 'CUSTOM_POST_TYPE';

    wp_reset_query();
    $args = array(
        'posts_per_page'   => -1,
        'orderby'          => 'post_date',
        'order'            => 'DESC',
        'post_type'        => $post_type,
        'post_status'      => 'publish',
        'suppress_filters' => true
    );
    query_posts( $args );
    wp_reset_postdata();

    //echo '<pre>';print_r($url);echo '</pre>';
    $posts_array = get_posts( $args );

    $output = '';
    if( !empty( $posts_array ) ){

        $chf = 'weekly';
        $pri = 1.0;

        foreach ( $posts_array as $p ) {
            $p->post_type   = $post_type;
            $p->post_status = 'publish';
            $p->filter      = 'sample';



            $url = array();

            if ( isset( $p->post_modified_gmt ) && $p->post_modified_gmt != '0000-00-00 00:00:00' && $p->post_modified_gmt > $p->post_date_gmt ) {
                $url['mod'] = $p->post_modified_gmt;
            } else {
                if ( '0000-00-00 00:00:00' != $p->post_date_gmt ) {
                    $url['mod'] = $p->post_date_gmt;
                } else {
                    $url['mod'] = $p->post_date;
                }
            }

            $url['loc'] = site_url().'/sample/all/'.$p->post_name;
            $url['chf'] = $chf;
            $url['pri'] = $pri;


            $output .= $wpseo_sitemaps->sitemap_url( $url );

            // Clear the post_meta and the term cache for the post, as we no longer need it now.
            // wp_cache_delete( $p->ID, 'post_meta' );
            // clean_object_term_cache( $p->ID, $post_type );
        }
    }

    // Grab last modified date
    $sql  = $wpdb->prepare(" SELECT MAX(p.post_modified_gmt) AS lastmod
		FROM	$wpdb->posts AS p
		WHERE post_status IN ('publish') AND post_type = %s ", $post_type );

    $mod = $wpdb->get_var( $sql );

    // Generate terms URLs
    $practitioner_terms = get_terms( 'TAXONOMY', 'orderby=count&hide_empty=0' );
    if( !empty( $practitioner_terms ) ){

        $pri = 1;
        $chf = 'weekly';

        foreach ($practitioner_terms as $key => $term ){

            $url = array();

            $url['loc'] = site_url().'/sample/'.$term->slug;
            $url['pri'] = $pri;
            $url['mod'] = $mod;
            $url['chf'] = $chf;
            $output .= $wpseo_sitemaps->sitemap_url( $url );

        }
    }

    // Generate permutation & combinations
    if( ( !empty( $practitioner_terms) ) &&  ( ! empty($posts_array ) ) ){

        $pri = 1;
        $chf = 'weekly';

        wp_reset_postdata();


        foreach ($practitioner_terms as $key => $term ){

            $args = array(
                'posts_per_page'   => -1,
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'post_type'        => $post_type,
                'post_status'      => 'publish',
                'suppress_filters' => true,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'TAXONOMY',
                        'field'    => 'slug',
                        'terms'    => $term->slug,
                        'operator' => 'IN'
                    ),
                ),
            );

            $posts_array = get_posts( $args );
            //echo '<pre>';print_r($posts_array);echo '</pre>';

            $url = array();

            foreach ($posts_array as $key => $p ){
                $url['loc'] = site_url().'/sample/'.$term->slug.'/'.$p->post_name;
                $url['pri'] = $pri;
                $url['mod'] = $mod;
                $url['chf'] = $chf;
                $output .= $wpseo_sitemaps->sitemap_url( $url );

            }

        }
    }


    if ( empty( $output ) ) {
        $wpseo_sitemaps->bad_sitemap = true;
        return;
    }

    //Build the full sitemap
    $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
    $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
    $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= $output . '</urlset>';

    //echo $sitemap;
    $wpseo_sitemaps->set_sitemap($sitemap);

}

/*********************************************************
 *  OR we can use $wpseo_sitemaps->register_sitemap( 'CUSTOM_KEY', 'METHOD' );
 ********************************************************/

add_action( 'init', 'ex_register_my_new_sitemap', 99 );
/**
 * On init, run the function that will register our new sitemap as well
 * as the function that will be used to generate the XML. This creates an
 * action that we can hook into built around the new
 * sitemap name - 'wp_seo_do_sitemap_my_new_sitemap'
 */
function ex_register_my_new_sitemap() {
    global $wpseo_sitemaps;
    $wpseo_sitemaps->register_sitemap( 'CUSTOM_KEY', 'ex_generate_origin_combo_sitemap' );
}

add_action( 'init', 'init_do_sitemap_actions' );

function init_do_sitemap_actions(){
    add_action( 'wp_seo_do_sitemap_our-CUSTOM_KEY', 'ex_generate_origin_combo_sitemap' );
}






