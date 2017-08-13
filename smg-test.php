<?php
/*
Plugin Name: SMG Test
Author: Evan Rose
Author URI: http://evanrose.com
Plugin URI: http://github.com/evanrose/smg-test
Version: 1.0
*/

/**
@ Add Image Sizes 
*/

add_image_size( 'smg_img', 186, 150,  1 );

/**
@ Include Styles
*/

function smg_load_css() {
    
    if ( is_single() ) {
    	
    	$plugin_url = plugin_dir_url( __FILE__ );
    	wp_enqueue_style( 'smg_style', $plugin_url . 'css/style.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'smg_load_css' );

/**
@ Check page type, get values for most recent post, build html to display after the_content
*/

function smg_most_recent( $content ) {

    if ( is_single() ) {

        $recent_post_args   = array(
            'numberposts' => 1,
            'post_status' => 'publish'
        );

        $recent_post        = wp_get_recent_posts( $recent_post_args, OBJECT );
        $recent_post_id     = $recent_post[0]->ID;

        $recent_cat_array   = get_the_category( $recent_post_id );
        $recent_cat         = $recent_cat_array[0]->cat_name;

        $recent_author_id   = get_post_field( 'post_author', $recent_post_id );
        $recent_author_name = get_the_author_meta( 'display_name', $recent_author_id );

        $recent_timestamp   = human_time_diff( get_the_time( 'U', $recent_post_id ), current_time( 'timestamp' ) ) . ' ago';

        $recent_post_html = '

            <div class="smg-rp-container">
                <span class="smg-img-container">' . get_the_post_thumbnail( $recent_post_id, 'smg_img' ) . '</span>
                <div class="smg-rp-cat-container">
                    <span class="smg-rp-cat">' . $recent_cat . '</span>
                    <span class="smg-rp-time mobile-only"> | <i>' . $recent_timestamp  . '</i></span>
                </div>
                <span class="smg-rp-h1"><a href="' . get_permalink( $recent_post_id ) . '">' . get_the_title( $recent_post_id ) . '</a></span>
                <span class="smg-rp-meta tablet-up">By: <a href="' . get_author_posts_url( $recent_author_id ) . '">' . $recent_author_name . '</a> <i>' .  $recent_timestamp . '</i></span>
            </div>
        ';

        $content .= $recent_post_html;
    }

    return $content;
}
add_filter( 'the_content', 'smg_most_recent' );