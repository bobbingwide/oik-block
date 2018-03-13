<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class oik_block_editor_opinions {


	public $opinions = array();
	
	public __construct() {
	
	
	}
	
	public 



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
