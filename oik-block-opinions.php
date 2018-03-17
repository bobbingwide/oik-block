<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */

/**
 * Syntax: oikwp oik-block-opinions.php [post_type |  post_id ] url=domain path=path
 * 
 * Main routine to gather opinions regarding which Editor to use to change existing content
 * in a site that was created prior to WordPress 5.0
 * 
 * Eventually this will become a wp-cli command
 * First we have to find out how to do this?
 * Now, or later?
 * 
 */
oik_block_opinions_loaded(); 

 
/**
 * Function to invoke when oik-locurl is loaded
 * 
 */
function oik_block_opinions_loaded() {
	$post_type_or_id = oik_batch_query_value_from_argv( 1, null );
	$post_type_or_id = trim( $post_type_or_id );
	if ( $post_type_or_id ) {
	
		echo $post_type_or_id . PHP_EOL;
	} else {
		echo "Syntax: oikwp oik-block-opinions.php [ post_type | post_id ] url=domain path=path" . PHP_EOL;
		die();
	}
	
	if ( is_numeric( $post_type_or_id ) ) {
		$post_id = $post_type_or_id;
		echo "Processing post ID: $post_id" . PHP_EOL;
	} else {
		$post_id = null;
		$post_type = $post_type_or_id;
		$post_type_object = oik_block_options_validate_post_type( $post_type );
		if ( $post_type_object ) {
			echo "Processing post type: $post_type" . PHP_EOL;
		} else {
			echo "Unrecognised post type: $post_type" . PHP_EOL;
			die();
		} 
	}
	
	oik_require( "admin/class-oik-block-editor-opinions.php", "oik-block" );
	add_action( "oik_block_prepare_opinions", "oik_block_prepare_opinions" );
	
	if ( $post_id ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$post_type = $post->post_type;
			oik_block_opinions_post( $post, $post_type );
		} else {
			echo "Post ID not found: $post_id" . PHP_EOL;
		}
	} else {
		oik_block_opinions_post_type( $post_type 
		);
	} 
		
	
}

function oik_block_options_validate_post_type( $post_type ) {
	$post_type_object = get_post_type_object( $post_type );
	return $post_type_object;
}

/**
 * Gathers the opinions for each unopinionated post for the post type
 *
 * @param string $post_type 
 */
function oik_block_opinions_post_type( $post_type ) {
	$opinions = oik_block_editor_opinions::instance();
	$opinions->gather_site_opinions();
	$opinions->gather_post_type_opinions( $post_type );
	$opinions->gather_all_post_opinions( $post_type );
	$opinions->report();
	$opinions->report_summary();
}


/**
 * Gather the opinions for the selected post
 */
function oik_block_opinions_post( $post, $post_type ) {
	echo "Post: {$post->ID}. Type: $post_type" . PHP_EOL;

	$opinions = oik_block_editor_opinions::instance();
	$opinions->gather_site_opinions();
	$opinions->gather_post_type_opinions( $post_type );
	$opinions->gather_post_opinions( $post );
	$opinions->report();
	$opinions->report_summary();
	$opinions->implement_decision( $post );
}

function oik_block_prepare_opinions() {
	oik_require( "opinions/class-oik-block-site-opinions.php", "oik-block" );
	$site_opinions = new oik_block_site_opinions();
  oik_require( "opinions/class-oik-block-type-opinions.php", "oik-block" );
	$type_opinions = new oik_block_type_opinions();
	oik_require( "opinions/class-oik-block-post-opinions.php", "oik-block" );
	$post_opinions = new oik_block_post_opinions();
	
//	oik_require( "opinions/class-oik-block-user-opinions.php", "oik-block" );
}

 
