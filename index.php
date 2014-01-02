<?php
/**
 * Plugin Name: Media Library Categories
 * Plugin URI: http://wordpress.org/plugins/wp-media-library-categories/
 * Description: Adds the ability to use categories in the media library.
 * Version: 1.3.2
 * Author: Jeffrey-WP
 */

/** register taxonomy for attachments */
function wpmediacategory_init() {
	// Default taxonomy
	$taxonomy = 'category';
	// Add filter to change the default taxonomy
	$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );
	register_taxonomy_for_object_type( $taxonomy, 'attachment' );
}
add_action( 'init', 'wpmediacategory_init' );

// load code that is only needed in the admin section
if( is_admin() ) {

	/** Add a category filter */
	function wpmediacategory_add_category_filter() {
		$screen = get_current_screen();
		if ( 'upload' == $screen->id ) {
			// Default taxonomy
			$taxonomy = 'category';
			// Add filter to change the default taxonomy
			$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );
			$dropdown_options = array( 'taxonomy' => $taxonomy, 'show_option_all' => __( 'View all categories' ), 'hide_empty' => false, 'hierarchical' => true, 'orderby' => 'name', );
			wp_dropdown_categories( $dropdown_options );
		}
	}
	add_action( 'restrict_manage_posts', 'wpmediacategory_add_category_filter' );


	/** Add custom Bulk Action to the select menus */
	function wpmediacategory_custom_bulk_admin_footer() {
		// default taxonomy
		$taxonomy = 'category';
		// add filter to change the default taxonomy
		$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );
		$terms = get_terms( $taxonomy, 'hide_empty=0' );
		if ( $terms && ! is_wp_error( $terms ) ) :

			echo '<script type="text/javascript">';
			echo 'jQuery(document).ready(function() {';
			foreach ( $terms as $term ) {
				echo 'jQuery(\'<option>\').val(\'cat_' . $term->term_taxonomy_id . '\').text(\''. esc_js( __( 'Category' ) ) . ': ' . esc_js( $term->name ) . '\').appendTo("select[name=\'action\']");';
				echo 'jQuery(\'<option>\').val(\'cat_' . $term->term_taxonomy_id . '\').text(\''. esc_js( __( 'Category' ) ) . ': ' . esc_js( $term->name ) . '\').appendTo("select[name=\'action2\']");';
			}
			echo 'jQuery(\'<option>\').val(\'cat_0\').text(\'' . esc_js( _e( 'Category' ) ) . ' ' . esc_js( strtolower( __( 'Remove' ) ) ) . '\').appendTo("select[name=\'action\']");';
			echo 'jQuery(\'<option>\').val(\'cat_0\').text(\'' . esc_js( _e( 'Category' ) ) . ' ' . esc_js( strtolower( __( 'Remove' ) ) ) . '\').appendTo("select[name=\'action2\']");';
			echo '});';
			echo '</script>';

		endif;
	}
	add_action( 'admin_footer-upload.php', 'wpmediacategory_custom_bulk_admin_footer' );


	/** Handle the custom Bulk Action */
	function wpmediacategory_custom_bulk_action() {
		global $wpdb;

		if ( ! isset( $_REQUEST['action'] ) )
			return;

		// is it a category?
		$sAction = ($_REQUEST['action'] != -1) ? $_REQUEST['action'] : $_REQUEST['action2'];
		if ( substr( $sAction, 0, 4 ) != 'cat_' )
			return;

		// security check
		check_admin_referer('bulk-media');

		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'post'
		if(isset($_REQUEST['media'])) {
			$post_ids = array_map('intval', $_REQUEST['media']);
		}

		if(empty($post_ids)) return;

		$sendback = admin_url( "upload.php?editCategory=1" );

		//$pagenum = $wp_list_table->get_pagenum();
		//$sendback = add_query_arg( 'paged', $pagenum, $sendback );

		$newCategory = str_replace('cat_', '', $sAction);

		foreach( $post_ids as $post_id ) {
			if ($newCategory == 0) {
				// remove category
				$wpdb->delete( $wpdb->term_relationships, array( 'object_id' => $post_id ) );
			} else {
				// update or insert category
				$wpdb->replace( $wpdb->term_relationships,
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $newCategory
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}

		wp_redirect( $sendback );
		exit();
	}
	add_action( 'load-upload.php', 'wpmediacategory_custom_bulk_action' );


	/** Display an admin notice on the page after changing category */
	function wpmediacategory_custom_bulk_admin_notices() {
		global $post_type, $pagenow;

		if($pagenow == 'upload.php' && $post_type == 'attachment' && isset($_GET['editCategory'])) {
			echo '<div class="updated"><p>' . __('Settings saved.') . '</p></div>';
		}
	}
	add_action( 'admin_notices', 'wpmediacategory_custom_bulk_admin_notices' );


	/** Add a link to media categories on the plugin page */
	function wpmediacategory_add_plugin_action_links( $links ) {
		// default taxonomy
		$taxonomy = 'category';
		// add filter to change the default taxonomy
		$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );
		return array_merge(
			array(
				'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit-tags.php?taxonomy=' . $taxonomy . '&amp;post_type=attachment">' . __('Categories') . '</a>'
			),
			$links
		);
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpmediacategory_add_plugin_action_links' );

}
?>