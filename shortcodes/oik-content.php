<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
 
/**
 * Implements [content] shortcode
 * 
 * Displays the website content summary
 * to enable analysis of the site for Gutenberg compatibility
 * 
 * 
 * 
 * Post type | UI | REST | Revisions | Editor | Count	|	Compatible?
 * --------- | -- | ---- | --------- | ------ | ------ | ------------
 * wp_block  | N  | Y    | ?         | Y      | nnnn		| Y / N / ?
 * post      | Y  | Y    | Y         | Y      | nnnn   | Y
 * 
 */

function oik_block_content( $atts=null, $content=null, $tag=null ) {

	//$post_types = bw_list_registered_post_types();
	oik_require( "shortcodes/oik-table.php" );
	stag( "table" );
	bw_table_header( bw_as_array( "Post-type,UI,REST,Revisions,Editable,Count,Editor") ); 
	stag( "tbody" );
	$post_types =  get_post_types();
	$total = 0;
	foreach ( $post_types as $post_type ) {
		$post_type_object = get_post_type_object( $post_type );
		//bw_trace2( $post_type_object, "post_type_object", false );
		$row = array();
		$row[] = $post_type;
		$row[] = $post_type_object->show_ui;
		$row[] = $post_type_object->show_in_rest;
		$row[] = post_type_supports( $post_type, "revisions" );
		$row[] = post_type_supports( $post_type, "editor" ); 
		$count = oik_block_count_posts( $post_type );
		$row[] = $count;
		$total += $count;
		$row[] = oik_block_post_type_compatible( $post_type, $post_type_object, $row );
		
		
		bw_tablerow( $row );
	}
	bw_tablerow( array( "Total", "", "", "", "", $total, "?" ) );
	etag( "tbody" );
	etag( "table" );
	return bw_ret();
}

/**
 * Determines which editor to use for the post type, if any
 *
 * For it to be editable using Gutenberg a post type must
 * - Have a UI
 * - Not be an "attachment"
 * - Show in REST
 * - Support "editor"
 *
 * These are the basic tests that Gutenberg performs. 
 * 
 * Additional tests are performed in the "gutenberg_can_edit_post_type" filter.
 * 
 * - If the post type does't support "revisions" then there could be a problem.
 * - Meta boxes for the post type are incompatible
 * - Associated fields support REST 
 * 
 * @param string $post_type
 * @param string $post_type_object
 * @return string Editor to use for the post type 
 */
function oik_block_post_type_compatible( $post_type, $post_type_object ) {
  if ( !( $post_type_object->show_ui ) ) {
		return "n/a";
	}
	if ( $post_type == "attachment" ) {
		return "Media";
	}
	if ( !( $post_type_object->show_in_rest ) ) {
		return "Classic-editor";
	}
	
	$can_edit = post_type_supports( $post_type, "editor" );
  $can_edit = apply_filters( "gutenberg_can_edit_post_type", $can_edit, $post_type );
	if ( $can_edit ) {
		$editor = "Gutenberg";
	} else {
		$editor = "Classic-editor";
	}
	return $editor;
}

/**
 * Returns the sum total of all posts for a post type
 *
 * This includes all different statuses, including Trash.
 * The user may undelete the content from Trash.
 *
 * @param string $post_type
 * @return integer total number of posts 
 */

function oik_block_count_posts( $post_type ) {
	$counts = (array) wp_count_posts( $post_type );
	//bw_trace2( $counts, "counts" );
	//$counts = implode( " ", $counts );
	$total = array_sum( $counts );
	return $total;
}

/**
 * Determines the compatibility of all posts in the post type
 * 
 * @param string $post_type
 * @return array( #Blocks, #Compatible, #Incompatible, #Unknown )
 */
function oik_block_posts_compatible( $post_type ) {
	$args = array( "post_type" => $post_type
							, "post_status" => "any"
							, "post_parent" => -1
							, "posts_per_page" => -1
							);
	$posts = bw_get_posts( $args );
	$compatibilities = array( "Blocks" => 0
													, "Compatible" => 0
													, "Incompatible" => 0
													, "Unknown" => 0
													);
	foreach ( $posts as $post ) {
		$compatible = oik_block_post_compatible( $post );
		$compatibilities[ $compatible ]++;
	}
	return $compatibilities;
}

function oik_block_post_compatible( $post ) {
 
	return "Blocks";
	 
}
	


/**
 
C:\apache\htdocs\wordpress\wp-content\plugins\oik-block\shortcodes\oik-content.php(36:0) oik_block_content(1) 287 2018-03-11T15:40:59+00:00 4.200809 0.179924 cf=genesis_loop,genesis_entry_content,the_content 30 31363 4194304/4194304 128M F=508 post_type_object WP_Post_Type Object
(
    [name] => post
    [label] => Psots
    [labels] => stdClass Object
        (
            [name] => Psots
            [singular_name] => Psot
            [add_new] => Add NEw
            [add_new_item] => Add A NEw Psot
            [edit_item] => Eidt Psot
            [new_item] => NEw Psot
            [view_item] => Veiw Psot
            [view_items] => Veiw Psots
            [search_items] => Saecrh Psots
            [not_found] => NO psots fuond.
            [not_found_in_trash] => NO psots fuond In BIn.
            [parent_item_colon] => 
            [all_items] => All Psots
            [archives] => Psot Acrihevs
            [attributes] => Psot Attirubets
            [insert_into_item] => Isnret itno psot
            [uploaded_to_this_item] => Ulpaoedd tO tihs psot
            [featured_image] => Featured Image
            [set_featured_image] => Set featured image
            [remove_featured_image] => Remove featured image
            [use_featured_image] => Use as featured image
            [filter_items_list] => Flietr psots lsit
            [items_list_navigation] => Psots lsit nvagitaoin
            [items_list] => Psots lsit
            [menu_name] => Psots
            [name_admin_bar] => Psot
        )

    [description] => 
    [public] => 1
    [hierarchical] => 
    [exclude_from_search] => 
    [publicly_queryable] => 1
    [show_ui] => 1
    [show_in_menu] => 1
    [show_in_nav_menus] => 1
    [show_in_admin_bar] => 1
    [menu_position] => 5
    [menu_icon] => 
    [capability_type] => post
    [map_meta_cap] => 1
    [register_meta_box_cb] => 
    [taxonomies] => Array
        (
        )

    [has_archive] => 
    [query_var] => 
    [can_export] => 1
    [delete_with_user] => 1
    [_builtin] => 1
    [_edit_link] => post.php?post=%d
    [cap] => stdClass Object
        (
            [edit_post] => edit_post
            [read_post] => read_post
            [delete_post] => delete_post
            [edit_posts] => edit_posts
            [edit_others_posts] => edit_others_posts
            [publish_posts] => publish_posts
            [read_private_posts] => read_private_posts
            [read] => read
            [delete_posts] => delete_posts
            [delete_private_posts] => delete_private_posts
            [delete_published_posts] => delete_published_posts
            [delete_others_posts] => delete_others_posts
            [edit_private_posts] => edit_private_posts
            [edit_published_posts] => edit_published_posts
            [create_posts] => edit_posts
        )

    [rewrite] => 
    [show_in_rest] => 1
    [rest_base] => posts
    [rest_controller_class] => WP_REST_Posts_Controller
)

*/
	
