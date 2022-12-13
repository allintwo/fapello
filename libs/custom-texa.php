<?php


function add_custom_taxonomies() {
    // Add new "Locations" taxonomy to Posts
    register_taxonomy('Model', 'post', array(
        // Hierarchical taxonomy (like categories)
        'hierarchical' => false,
        'show_in_rest' => true,
        // This array of options controls the labels displayed in the WordPress Admin UI
        'labels' => array(
            'name' => _x( 'Model', 'taxonomy general name' ),
            'singular_name' => _x( 'Location', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Model' ),
            'all_items' => __( 'All Locations' ),
            'parent_item' => __( 'Parent Model' ),
            'parent_item_colon' => __( 'Parent Model:' ),
            'edit_item' => __( 'Edit Model' ),
            'update_item' => __( 'Update Model' ),
            'add_new_item' => __( 'Add New Model' ),
            'new_item_name' => __( 'New Model Name' ),
            'menu_name' => __( 'Model' ),
        ),
        // Control the slugs used for this taxonomy
        'rewrite' => array(
            'slug' => 'Model', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
}
add_action( 'init', 'add_custom_taxonomies', 0 );