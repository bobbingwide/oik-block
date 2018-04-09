<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */

/**
 * Syntax: oikwp oik-block-opinions.php subcommand [post_type |  post_id ]  url=domain path=path
 * 
 * Main routine to gather opinions regarding which Editor to use to change existing content
 * in a site that was created prior to WordPress 5.0
 * 
 * Eventually this will become a wp-cli command
 * First we have to find out how to do this?
 * Now, or later?
 * 
 * 
 */
oik_block_opinions_loaded(); 

 
/**
 * Function to invoke when oik-block-opinions is loaded
 *
 * Waits until "run_oik-block-opinions.php" action is run.
 * 
 */
function oik_block_opinions_loaded() {
	add_action( "run_oik-block-opinions.php", "oik_block_opinions_run" );
}

/**
 * Runs oik-block-opinions
 */
function oik_block_opinions_run() {
	$subcommand = oik_batch_query_value_from_argv( 1, null );
	$post_type_or_id = oik_batch_query_value_from_argv( 2, null );
	$post_type_or_id = trim( $post_type_or_id );
	
	oik_require( "admin/class-oik-block-editor-opinions.php", "oik-block" );
	add_action( "oik_block_prepare_opinions", "oik_block_prepare_opinions" );
	
	ini_set('memory_limit','1024M');
	
	$level = oik_block_determine_level( $post_type_or_id );
	oik_block_opinions_subcommand( $subcommand, $level, $post_type_or_id );
	
}

/**
 * Determines the level at which to perform the subcommand
 *
 * Level | Means
 * ----- | -------------
 * site  | Perform the subcommand at the site level, including plugins, themes and content
 * type  | Perform the subcommand at the post type level. Includes processing all posts for a post type
 * post  | Perform the subcommand against an individual post
 * user  | Perform the subcommand for a selected user; --user=<id|login|email>
 *
 * @param string $post_type_or_id post type or post ID
 * @return string $level 
 */
function oik_block_determine_level( $post_type_or_id ) {
	$level = 'site';
	if ( $post_type_or_id ) {
		echo $post_type_or_id . PHP_EOL;
	
		
		if ( is_numeric( $post_type_or_id ) ) {
			$post_id = $post_type_or_id;
			echo "Processing post ID: $post_id" . PHP_EOL;
			$post = get_post( $post_id );
			$level = 'post';
		} else {
			$post_id = null;
			$post_type = $post_type_or_id;
			$post_type_object = oik_block_options_validate_post_type( $post_type );
			if ( $post_type_object ) {
				echo "Processing post type: $post_type" . PHP_EOL;
				$level = 'type';
			} else {
				echo "Unrecognised/unsupported post type: $post_type" . PHP_EOL;
				die();
			} 
		}
	} else {
	 // 'site' or 'user' - @TODO Add logic for 'user' level
	}
	
	return $level;
}

/**
 * Implements subcommand at the required level
 *
 * Each level has its own subcommands class and methods.
 * They all use the oik_block_editor_opinions and oik_block_editor_opinion classes.
 
 * @TODO Complete this table... somewhere
 * @TODO Use  WP-CLI logic to determine valid subcommands by level
 * 
 * Subcommand	| Site | Type | Post | User
 
 * ---------- | ---- | ---- | ---- | -----
 * status			|  Y   |  Y   |  Y   | 
 * reset 			|	 Y	 |			|			 |
 * list
 * consider
 * decide
 * test 
 * convert
 */
function oik_block_opinions_subcommand( $subcommand, $level, $post_type_or_id  ) {
	if ( !$subcommand ) {
		echo "Syntax: oikwp oik-block-opinions.php subcommand [ post_type | post_id ] url=domain path=path" . PHP_EOL;
		echo "No subcommand specified. Defaulting to 'status'." . PHP_EOL;
		$subcommand = "status";
	}
	
	oik_require( "admin/class-oik-block-subcommands.php", "oik-block" );
	oik_require( "admin/class-oik-block-{$level}-subcommands.php", "oik-block" );
	$class = "oik_block_{$level}_subcommands";
	$subcommands = new $class( $level, $post_type_or_id );
	
	if ( method_exists( $subcommands, $subcommand ) ) {
		$subcommands->$subcommand( $post_type_or_id );
	}
}


/**
 * Validates post_type
 *
 * Note: 'revision' is not supported but other's which may not be editable are.
 * 
 * @param string $post_type
 * @return object post type object for a valid post type, except 'revision'
 */
function oik_block_options_validate_post_type( $post_type ) {
	if ( $post_type === 'revision' ) {
		echo "post type revision not supported" . PHP_EOL;
		return null;
	}
	$post_type_object = get_post_type_object( $post_type );
	return $post_type_object;
}

/**
 * Prepares for opinion gathering
 *
 * Opinion gathering is controlled by methods in class oik_block_editor_opinions
 */
function oik_block_prepare_opinions() {
	oik_require( "opinions/class-oik-block-site-opinions.php", "oik-block" );
	$site_opinions = new oik_block_site_opinions();
  oik_require( "opinions/class-oik-block-type-opinions.php", "oik-block" );
	$type_opinions = new oik_block_type_opinions();
	oik_require( "opinions/class-oik-block-post-opinions.php", "oik-block" );
	$post_opinions = new oik_block_post_opinions();
	
//	oik_require( "opinions/class-oik-block-user-opinions.php", "oik-block" );
}

 
