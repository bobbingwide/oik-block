<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
 
/**
 * Implements [content] shortcode
 * 
 * Displays the website content summary to enable analysis of the site for Gutenberg ( Block editor ) compatibility
 * 
 * 
 * Post-type | UI | REST | Revisions | Editable | Count	| Editor
 * --------- | -- | ---- | --------- | ------ | ------ | ------------
 * post |  1 | 1 | 1 | 1 | 1 | Gutenberg
 * page | 1 | 1 | 1 | 1 | 1 | Gutenberg
 * attachment | 1 | 1 |  |  | 0 | Media
 * revision | | | | | 1 | Revisions
 * nav_menu_item | | | | 1 | 0 | n/a
 * custom_css | | | 1 | | 0 | n/a
 * customize_changeset | | | | | 0 | n/a
 * oembed_cache | | | | 1 | 0 | n/a
 * Total | | | | | 3 | ?
 * 
 * @param array $atts shortcode attributes
 * @param 
 */

function oik_block_content( $atts=null, $content=null, $tag=null ) {
	oik_block_reset_editable();
	//$post_types = bw_list_registered_post_types();
	oik_require( "shortcodes/oik-table.php" );
	oik_require( "libs/class-BW-table.php", "oik-block" );
	BW_table::header( bw_as_array( "Post-type,UI,REST,Revisions,Editable,Count,Editor") ); 
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
		$editor = oik_block_post_type_compatible( $post_type, $post_type_object, $row );
		$row[] = $editor;
		
		oik_block_count_editable( $count, $editor );
		
		
		BW_table::row( $row );
	}
	BW_table::row( array( "Total", "", "", "", "", $total, "?" ) );
	BW_table::footer();
	
	oik_block_report_editable();
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
 * - If the post type doesn't support "revisions" then there could be a problem.
 * - Meta boxes for the post type are incompatible
 * - Associated fields support REST 
 * 
 * @param string $post_type
 * @param string $post_type_object
 * @return string Editor to use for the post type 
 */
function oik_block_post_type_compatible( $post_type, $post_type_object ) {
	if ( $post_type == "attachment" ) {
		return "Media";
	}
	if ( $post_type == "revision" ) {
		return "Revisions";
	}
  if ( !( $post_type_object->show_ui ) ) {
		return "n/a";
	}
	if ( !( $post_type_object->show_in_rest ) ) {
		return "Classic";
	}
	
	$can_edit = post_type_supports( $post_type, "editor" );
  $can_edit = apply_filters( "gutenberg_can_edit_post_type", $can_edit, $post_type );
	if ( $can_edit ) {
		$editor = "Block";
	} else {
		$editor = "Classic";
	}
	return $editor;
}

/**
 * Returns the sum total of all posts worth processing for a post type
 *
 * wp_count_posts includes all different statuses, including 'trash', 'auto-draft' and 'spam'.
 * Althought the user may undelete the content from Trash we don't need to worry about them.
 * There really shouldn't be that many Auto-drafts!
 * And we don't care about spam.
 * 
 * Post statuses that are worth considering include
 * `
    [publish] => 10
    [future] => 0
    [draft] => 0
    [pending] => 0
    [private] => 0
    [inherit] => 0
 * `
 * @param string $post_type
 * @return integer total number of posts 
 */

function oik_block_count_posts( $post_type ) {
	$counts = (array) wp_count_posts( $post_type );
	unset( $counts['auto-draft'] );
	unset( $counts['trash'] );
	unset( $counts['spam'] );
	bw_trace2( $counts, "counts" );
	//$counts = implode( " ", $counts );
	$total = array_sum( $counts );
	return $total;
}

/**
 * Resets the globals for the summary table
 */
function oik_block_reset_editable() {
	unset( $GLOBALS[ 'bw_editable_plugins' ] );
	unset( $GLOBALS[ 'bw_editable_counts' ] );
}

/**
 * Counts the editable posts by $editor
 */
function oik_block_count_editable( $count, $editor ) {
	global $bw_editable_plugins, $bw_editable_counts;
	if ( !isset( $bw_editable_counts[$editor] ) ) {
		$bw_editable_counts[ $editor ] = 0;
	}
	$bw_editable_counts[ $editor ] += $count;
	
	if ( !isset( $bw_editable_plugins[ $editor ] ) ) {
		$bw_editable_plugins[ $editor ] = 0;
	}
	$bw_editable_plugins[ $editor ]++;
}

/**
 * Displays the summary report of editable content
 * 
 * For example... for a brand new site. 
 
 * Editor | Types | Count
 * ----- | ------ | --------
 * Block | 2 | 2
 * Media | 1 | 0
 * Revisions | 1 | 1
 * n/a | 5 | 0
 * Classic | 0 | 0
 * Total | 9 | 3
 *
 * 
 */
function oik_block_report_editable() {
	
	global $bw_editable_plugins, $bw_editable_counts;
	BW_table::header( bw_as_array( "Editor,Types,Count") ); 
	foreach ( $bw_editable_plugins as $editable => $count ) {
		BW_table::row( array(  $editable, $count, $bw_editable_counts[ $editable ] ) );
	}
	BW_table::row( array( "Total", array_sum( $bw_editable_plugins ), array_sum( $bw_editable_counts ) ) );
	
	BW_table::footer();
}


/**
 * Wrapper to table formatting for cli, csv and HTML 
 */	
function oik_block_table_header( $header ) {
	BW_table::header( $header );
}

function oik_block_table_row( $row ) {

	oik_require( "libs/class-BW-tablephp", "oik-block" );
	BW_table::row( $row );

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
	
