<?php
/**
 * Plugin Name: media library categories
 * Description: Adds the ability to use categories in the media library.
 * Version: 1.0
 * Author: Jeffrey-WP
 */

/** register taxonomy for attachments */
function wpmediacategory_init() {
	register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init', 'wpmediacategory_init' );

/** Add a category filter */
function wpmediacategory_add_category_filter() {
    $screen = get_current_screen();
    if ( 'upload' == $screen->id ) {
        $dropdown_options = array( 'show_option_all' => __( 'View all categories', 'wpmediacategory' ), 'hide_empty' => false, 'hierarchical' => true, 'orderby' => 'name', );
        wp_dropdown_categories( $dropdown_options );
    }
}
add_action( 'restrict_manage_posts', 'wpmediacategory_add_category_filter' );
?>