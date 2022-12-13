<?php

// bg worker
register_activation_hook( __FILE__, 'fapellocrn_activation' );
add_action( 'fapello_hourly_event', 'fapello_do_this_hourly' );

function fapellocrn_activation() {
    wp_schedule_event( time(), 'hourly', 'fapello_hourly_event' );
}

function fapello_do_this_hourly() {
    // do something every hour

    // update model - likes, comments, followers, views

}