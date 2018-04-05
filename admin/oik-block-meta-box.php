<?php

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
 
/**
 * Displays the Preferred editor meta box
 * 
 * 
 * The Preferred editor is not forced on the user. 
 * It displays the setting for the preferred editor to use for the current post
 * and explanations for that setting. 
 * 
 */
function oik_block_meta_box( $post, $metabox ) {
  oik_require( "shortcodes/oik-content.php", "oik-block" );
	
	oik_block_display_post_opinions( $post );
	
	//oik_block_show_in_rest( $post ); 
	//oik_block_revisions( $post );

	
	$preferred_editor = oik_block_get_preferred_editor( $post );
	$preferred_editor_options = oik_block_get_preferred_editor_options();
	//BW_::p( __( "Editor selection", "oik-block" ) );
	//e( $preferred_editor );
	
  stag( 'table', "form-table" );
	BW_::bw_select( "_oik_block_editor", "Preferred Editor", $preferred_editor, array( "#options" => $preferred_editor_options, "#optional" => true ) );
	
  etag( "table" );
	
	bw_flush();
	

}


function oik_block_display_post_opinions( $post ) {
	//oik_require( "admin/class-oik-block-editor-opinions.php", "oik-block" );
	//add_action( "oik_block_prepare_opinions", "oik_block_meta_box_prepare_opinions" );
	oik_require( "oik-block-opinions.php", "oik-block" );
	oik_require( "admin/class-oik-block-editor-opinions.php", "oik-block" );
	add_action( "oik_block_prepare_opinions", "oik_block_prepare_opinions" );
	oik_block_opinions_subcommand( "consider", "type", $post->post_type );
	oik_block_opinions_subcommand( "consider", "post", $post->ID  );
}


/**
 * Displays opinion regarding support for 'revisions'
 */
function oik_block_revisions( $post ) {

  $revisions = post_type_supports( $post->post_type, "revisions" );
	if ( $revisions ) {
		p( "Revisions are supported. Block editor OK" );
	}else {
		p( "Revisions not supported. Block editor not recommended" );
  }
}

/**
 * Displays opinion regarding "show_in_rest" 
 */
function oik_block_show_in_rest( $post ) {
	$post_type_object = get_post_type_object( $post->post_type );
	if ( $post_type_object->show_in_rest ) {
		p( "Block editor supported. Show in REST enabled." );
	} else {
		p( "Classic required. Show in REST not enabled." );
	}
}


/**
 * Obtains the currently set value for the Preferred editor
 * 
 * @param object $post Selected post
 * @return string code for the preferred editor
 */
function oik_block_get_preferred_editor( $post ) {
	$preferred_editor = null;
	$preferred_editor = get_post_meta( $post->ID, "_oik_block_editor", true );
	
	return $preferred_editor;
}

/**
 * Returns set of Editor options
 *
 * @return array Associative array of options
 */
function oik_block_get_preferred_editor_options() {
	$options = array( "AM" => __( "Any (Mandatory)", "oik-block" )
									, "AO" => __( "Any", "oik-block" )
									, "BM" => __( "Block (Mandatory)", "oik-block" )
									, "BO" => __( "Block", "oik-block" )
									, "CM" => __( "Classic (Mandatory)", "oik-block" )
									, "CO" => __( "Classic", "oik-block" )
									);
	return $options;
}

/**
 * Gathers expert opinions to determine the preferred editor.
 * 
 * Each opinion is an editor_opinion object of the form:
 * 
 * string $preferred_editor values A=Any / Ambivalent, B=Block, C=Classic, other=tbc
 * bool $mandatory true if there's no choice in the matter
 * string $observation
 * string $choice_of_action
 * 
 * Opinions are gathered using the 'oik_block_gather_opinions' filter passing $opinions, $post
 
 */
function oik_block_gather_editor_opinions( $post ) {

	$opinions = array();
	$opinions = apply_filters( "oik_block_gather_editor_opinions", $opinions, $post );
	return $opinions;

}

/**
 * Opinions are analysed to determine which is the most sensible choice
 * 
 * @array $opinions
 * @return $preferred_editor
 */
function oik_block_analyse_editor_opinions( $opinions ) {

}

function oik_block_display__editor_opinions( $opinions ) {
	foreach ( $opinions as $opinion ) {
		$opinion->display();
	}

}


	 

