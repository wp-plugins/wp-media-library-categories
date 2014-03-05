<?php
/**
 * Plugin Name: Media Library Categories
 * Plugin URI: http://wordpress.org/plugins/wp-media-library-categories/
 * Description: Adds the ability to use categories in the media library.
 * Version: 1.4.4
 * Author: Jeffrey-WP
 * Author URI: http://codecanyon.net/user/jeffrey-wp/?ref=jeffrey-wp
 */

/** register taxonomy for attachments */
function wpmediacategory_init() {
	// Default taxonomy
	$taxonomy = 'category';
	// Add filter to change the default taxonomy
	$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );

	$args = array(
		'hierarchical' => true,  // hierarchical: true = display as categories, false = display as tags
		'show_admin_column' => true
	);
	register_taxonomy( $taxonomy, array( 'attachment' ), $args );
}
add_action( 'init', 'wpmediacategory_init' );

// load code that is only needed in the admin section
if( is_admin() ) {

	/** Custom walker for wp_dropdown_categories, based on https://gist.github.com/stephenh1988/2902509 */
	class wpmediacategory_walker_category_filter extends Walker_CategoryDropdown{

		function start_el(&$output, $category, $depth, $args) {
			$pad = str_repeat('&nbsp;', $depth * 3);
			$cat_name = apply_filters('list_cats', $category->name, $category);

			if( !isset($args['value']) ) {
				$args['value'] = ( $category->taxonomy != 'category' ? 'slug' : 'id' );
			}

			$value = ($args['value']=='slug' ? $category->slug : $category->term_id );

			$output .= "\t<option class=\"level-$depth\" value=\"".$value."\"";
			if ( $value === (string) $args['selected'] ) {
				$output .= ' selected="selected"';
			}
			$output .= '>';
			$output .= $pad.$cat_name;
			if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. $category->count .')';

			$output .= "</option>\n";
		}

	}


	/** Add a category filter */
	function wpmediacategory_add_category_filter() {
		$screen = get_current_screen();
		if ( 'upload' == $screen->id ) {
			// Default taxonomy
			$taxonomy = 'category';
			// Add filter to change the default taxonomy
			$taxonomy = apply_filters( 'wpmediacategory_taxonomy', $taxonomy );
			$dropdown_options = array(
				'taxonomy'        => $taxonomy,
				'name'            => $taxonomy,
				'show_option_all' => __( 'View all categories' ),
				'hide_empty'      => false,
				'hierarchical'    => true,
				'orderby'         => 'name',
				'walker'          => new wpmediacategory_walker_category_filter(),
				'value'           => 'slug'
			);
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
			echo 'jQuery(\'<optgroup id="wpmediacategory_optgroup1" label="' .  html_entity_decode( __( 'Categories' ), ENT_QUOTES, 'UTF-8' ) . '">\').appendTo("select[name=\'action\']");';
			echo 'jQuery(\'<optgroup id="wpmediacategory_optgroup2" label="' .  html_entity_decode( __( 'Categories' ), ENT_QUOTES, 'UTF-8' ) . '">\').appendTo("select[name=\'action2\']");';

			// add categories
			foreach ( $terms as $term ) {
				$sTxtAdd = esc_js( __( 'Add' ) . ': ' . $term->name );
				echo "jQuery('<option>').val('wpmediacategory_add_" . $term->term_taxonomy_id . "').text('". $sTxtAdd . "').appendTo('#wpmediacategory_optgroup1');";
				echo "jQuery('<option>').val('wpmediacategory_add_" . $term->term_taxonomy_id . "').text('". $sTxtAdd . "').appendTo('#wpmediacategory_optgroup2');";
			}
			// remove categories
			foreach ( $terms as $term ) {
				$sTxtRemove = esc_js( __( 'Remove' ) . ': ' . $term->name );
				echo "jQuery('<option>').val('wpmediacategory_remove_" . $term->term_taxonomy_id . "').text('". $sTxtRemove . "').appendTo('#wpmediacategory_optgroup1');";
				echo "jQuery('<option>').val('wpmediacategory_remove_" . $term->term_taxonomy_id . "').text('". $sTxtRemove . "').appendTo('#wpmediacategory_optgroup2');";
			}
			// remove all categories
			echo "jQuery('<option>').val('wpmediacategory_remove_0').text('" . esc_js(  __( 'Delete all' ) ) . "').appendTo('#wpmediacategory_optgroup1');";
			echo "jQuery('<option>').val('wpmediacategory_remove_0').text('" . esc_js(  __( 'Delete all' ) ) . "').appendTo('#wpmediacategory_optgroup2');";
			echo "});";
			echo "</script>";

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
		if ( substr( $sAction, 0, 16 ) != 'wpmediacategory_' )
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

		foreach( $post_ids as $post_id ) {

			if ( is_numeric( str_replace('wpmediacategory_add_', '', $sAction) ) ) {
				$nCategory = str_replace('wpmediacategory_add_', '', $sAction);

				// update or insert category
				$wpdb->replace( $wpdb->term_relationships,
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $nCategory
					),
					array(
						'%d',
						'%d'
					)
				);

			} else if ( is_numeric( str_replace('wpmediacategory_remove_', '', $sAction) ) ) {
				$nCategory = str_replace('wpmediacategory_remove_', '', $sAction);

				// remove all categories
				if ($nCategory == 0) {
					$wpdb->delete( $wpdb->term_relationships,
						array(
							'object_id' => $post_id
						),
						array(
							'%d'
						)
					);
				// remove category
				} else {
					$wpdb->delete( $wpdb->term_relationships,
						array(
							'object_id' => $post_id,
							'term_taxonomy_id' => $nCategory
						),
						array(
							'%d',
							'%d'
						)
					);
				}

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