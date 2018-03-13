<?php

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
function oik_block_meta_box( $post, $metabox ) {
  oik_require( "shortcodes/oik-content.php", "oik-block" );
	
	oik_block_revisions( $post );

	BW_::p( __( "Editor selection", "oik-block" ) );
	
	bw_flush();
	

}

function oik_block_revisions( $post ) {

  $revisions = post_type_supports( $post->post_type, "revisions" );
	if ( $revisions ) {
		p( "Revisions are supported. Block editor OK" );
	}else {
		p( "Revisions not supported. Block editor not recommended" );
  }
}


	 

