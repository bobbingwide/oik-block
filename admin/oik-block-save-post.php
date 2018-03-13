<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
/**
 * Lazy implementation of save_post
 * 
 * Checks the post content to determine the preferred_editor
 *
 *
 * @param ID $id - the ID of the post being updated
 * @param post $post - the post object
 * @param bool $update - true more often than not
 */
function oik_block_lazy_save_post( $id, $post, $update ) {
	bw_trace2();
	
	$preferred_editor = oik_block_set_preferred_editor( $id, $post, $update );
	
	update_post_meta( $id, "_oik_block_editor", $preferred_editor );
}

/**
 * Returns the preferred editor for the post
 * 
 * @return string $editor
 */
function oik_block_set_preferred_editor( $id, $post, $update ) {

	$editor = bw_array_get( $_REQUEST, "_oik_block_editor", null );
	if ( !$editor ) {
	
		$editor = "A";
	}
	return $editor;
}
