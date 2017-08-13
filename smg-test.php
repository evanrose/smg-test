<?php
/*
Plugin Name: SMG Test
*/

/**
@ Add Image Sizes 
*/

add_image_size( 'smg_img', 186, 150,  1 );

/**
@ Include Styles
*/

function smg_load_css() {
    
    if ( ! is_admin() ) {
    	
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

        $recent_post_args   = array();
        $recent_post_args   = array(
            'numberposts' => 1
        );

        $recent_post_array 	= wp_get_recent_posts();
        $recent_post 		= $recent_post_array[0];

        $the_category_array = get_the_category( $recent_post['ID'] );
        $the_category 		= $the_category_array[0]->cat_name;

        $author_id          = get_post_field( 'post_author', $recent_post['ID'] );
        $author_name        = get_the_author_meta( 'display_name', $author_id );

        $timestamp          = human_time_diff( get_the_time('U', $recent_post['ID'] ), current_time('timestamp') ) . ' ago';

        $recent_post_html = '

            <div class="smg_rp_container">
                <span class="smg-img-container">' . get_the_post_thumbnail( $recent_post['ID'], 'smg_img') . '</span>
                <div class="smg-rp-cat-container">
                    <span class="smg-rp-cat">'  . $the_category . '</span>
                   <span class="smg-rp-time mobile-only"> | <i>' . $timestamp  . '</i></span>
                </div>
                <span class="smg-rp-h1">'   . $recent_post['post_title'] . '</span>
                <span class="smg-rp-meta tablet-up">By: <a href="'. get_author_posts_url( $author_id ) .'">'. $author_name .'</a> <i>'.  $timestamp .'</i></span>
            </div>

        ';

        $content .= $recent_post_html;
    }

    return $content;
}
add_filter( 'the_content', 'smg_most_recent' );