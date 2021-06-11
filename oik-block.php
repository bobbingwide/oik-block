<?php
/**
 * Plugin Name: oik-block
 * Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-block
 * Description: Gut feel - helps you form Gutenberg compatibility opinions.
 * Author: Herb Miller
 * Author URI: https://herbmiller.me/about/mick
 * Version: 0.1.0
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package oik-block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Implements "gutenberg_can_edit_post_type" for oik-blocks 
 * 
 * Here we'll implement logic to test whether or not we're going to allow Gutenberg to edit the content
 *
 * If the post type doesn't support revisions then we may not want to use Gutenberg,
 * but this shouldn't prevent us from allowing the user to edit the content using the block editor.
 * Disabling the test for now. Herb 2018/04/04
 *
 */
function oik_block_gutenberg_can_edit_post_type( $can_edit, $post_type ) {
	bw_trace2();
	//$can_edit = post_type_supports( $post_type, "revisions" );
	return $can_edit;
}

/**
 * Implements actions for "oik_loaded"
 *
 * Now we know it's safe to respond to shortcodes
 */
function oik_block_oik_loaded() {
	add_action( "oik_add_shortcodes", "oik_block_oik_add_shortcodes" );
	add_filter( "oik_block_query_inline_shortcodes", "oik_block_query_inline_shortcodes" );
	add_filter( "oik_block_query_incompatible_shortcodes", "oik_block_query_incompatible_shortcodes" );
}

/** 
 * Add our shortcodes
 */
function oik_block_oik_add_shortcodes() {
	bw_add_shortcode( "contents", "oik_block_contents", oik_path("shortcodes/oik-contents.php", "oik-block"), false );
	bw_add_shortcode( "guts", "oik_block_guts", oik_path( "shortcodes/oik-guts.php", "oik-block" ), false );
	bw_add_shortcode( "content", "oik_block_content", oik_path( "shortcodes/oik-content.php", "oik-block" ), false );
}

/**
 * Logic run when plugin loaded
 */
function oik_block_loaded() {
	add_filter( 'gutenberg_can_edit_post_type', 'oik_block_gutenberg_can_edit_post_type', 10, 2 );
	add_action( "oik_loaded", "oik_block_oik_loaded" );
	add_action( "plugins_loaded", "oik_block_plugins_loaded", 100 );
  if ( !defined('DOING_AJAX') ) {
    add_action( "save_post", "oik_block_save_post", 10, 3 );
		add_action( 'add_meta_boxes', 'oik_block_add_meta_boxes', 10, 2 );
  }
}

/**
 * Implements 'plugins_loaded' action for oik-block
 * 
 * Prepares use of shared libraries if this has not already been done.
 */
function oik_block_plugins_loaded() {
	oik_block_boot_libs();
	oik_require_lib( "bwtrace" );
}

/**
 * Boot up process for shared libraries
 * 
 * ... if not already performed
 */
function oik_block_boot_libs() {
	if ( !function_exists( "oik_require" ) ) {
		$oik_boot_file = __DIR__ . "/libs/oik_boot.php";
		$loaded = include_once( $oik_boot_file );
	}
	oik_lib_fallback( __DIR__ . "/libs" );
}

/** 
 * Implements "add_meta_boxes" for oik-block 
 *
 * Adds the meta box to enable the user to set the preferred editor for the post
 * 
 */
function oik_block_add_meta_boxes( $post_type, $post ) {
	if ( !did_action( 'oik_admin_menu')) {
		oik_block_plugins_loaded();
	}
		oik_require( "admin/oik-block-meta-box.php", "oik-block" );
		add_meta_box( 'oik_block', __( "Editor selection", 'oik-block' ), 'oik_block_meta_box', $post_type, 'normal', 'default',
        [ '__back_compat_meta_box' => true ]
        );
	
}

/**
 * Implements save for the oik_block meta box
 *
 * We invoke the logic as a lazy function.
 *
 * @param ID $id - the ID of the post being updated
 * @param post $post - the post object
 * @param bool $update - true more often than not
 */
function oik_block_save_post( $id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		// Ignore autosaves
	} else {
		oik_require( "admin/oik-block-save-post.php", "oik-block" );
		oik_block_lazy_save_post( $id, $post, $update );
	}
}

/**
 * Queries inline shortcodes for oik and related plugins
 * 
 * @TODO Determine if we need to know the component for the shortcode.
 * This may help us decide whether or not to deactivate the plugin
 * See also the schunter plugin.
 * 
 * @param array $inline_shortcodes
 * @return array $inline_shortcodes
 */ 
function oik_block_query_inline_shortcodes( $inline_shortcodes ) {
	oik_require( "converter/class-shortcode-converter.php", "oik-block" );
	$shortcode_converter = new Shortcode_converter();
	$inline_shortcodes = $shortcode_converter->query_inline_shortcodes( $inline_shortcodes );
	return $inline_shortcodes;
}

/**
 * Queries incompatible shortcodes for oik and related plugins
 * 
 * @TODO Determine if we need to know the component for the shortcode.
 * This may help us decide whether or not to deactivate the plugin
 * See also the schunter plugin.
 * 
 * @param array $inline_shortcodes
 * @return array $inline_shortcodes
 */ 
function oik_block_query_incompatible_shortcodes( $incompatible_shortcodes ) {
	oik_require( "converter/class-shortcode-converter.php", "oik-block" );
	$shortcode_converter = new Shortcode_converter();
	$incompatible_shortcodes = $shortcode_converter->query_incompatible_shortcodes( $incompatible_shortcodes );
	return $incompatible_shortcodes;
}

oik_block_loaded();





