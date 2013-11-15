<?php
/**
 * Plugin Name: Media Library Categories
 * Plugin URI: http://wordpress.org/plugins/wp-media-library-categories/
 * Description: Adds the ability to use categories in the media library.
 * Version: 1.2
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
		$dropdown_options = array( 'show_option_all' => __( 'View all categories' ), 'hide_empty' => false, 'hierarchical' => true, 'orderby' => 'name', );
		wp_dropdown_categories( $dropdown_options );
	}
}
add_action( 'restrict_manage_posts', 'wpmediacategory_add_category_filter' );

/** Add a link to media categories on the plugin page */
function wpmediacategory_add_plugin_action_links( $links ) {
	return array_merge(
		array(
			'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit-tags.php?taxonomy=category&amp;post_type=attachment">' . __('Categories') . '</a>'
		),
		$links
	);
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpmediacategory_add_plugin_action_links' );
?>