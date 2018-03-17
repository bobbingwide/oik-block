<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_type_opinions {


	/**
	 * Array of methods to invoke to when gathering our thoughts and forming opinions.
	 */
	private $thoughts = array( "supports_editor"
													 , "show_in_rest"
													 , "can_edit_post_type" 
													 , "taxonomies_are_rest" 
													 );
	public $post_type = null;		
	public $post_type_object = null;	 
	public $can_edit = true; 	
	
	public $level = 'T'; // Post type opinions								

	public function __construct() {
		echo __CLASS__ . PHP_EOL;
		$this->post_type = null;
		add_filter( "oik_block_gather_type_opinions", array( $this, "form_opinions" ), 1, 2 );
	}	
	
	/**
	 * Forms the opinions for this class
	 * 
	 * @param array $opinions
	 * @param string $post_type
	 * @return array $opinions
	 */
	public function form_opinions( $opinions, $post_type ) {
		$this->post_type = $post_type;
		$this->post_type_object = get_post_type_object( $post_type );
		$this->can_edit = true;
		bw_trace2();
		foreach ( $this->thoughts as $thought ) {
		 if ( method_exists( $this, $thought ) ) {
				$opinion = $this->$thought();
			} else {
				gob();
			}
			 
			 
			if ( $opinion ) {
				$opinions[] = $opinion;
			}
		}
		bw_trace2();
		return $opinions;
	}
	
	/**
	 * If the post type doesn't support the editor then we should force 'C'
	 * Do we have examples?
	 */
	public function supports_editor() {
		if ( post_type_supports( $this->post_type, 'editor' ) ) {
			return null;
		} else {
			$this->can_edit = false;
			return new oik_block_editor_opinion( 'C', true, 'T', "Post type does not support 'editor'" );
		}
	}
	
	public function show_in_rest() {
		if ( $this->post_type_object->show_in_rest ) {
			$this->can_edit = true;
			return new oik_block_editor_opinion( 'A', false, 'T', "REST API enabled." );
		} else {
			$this->can_edit = false;
			return new oik_block_editor_opinion( 'C', true, 'T', "REST API not enabled.", "Set show_in_rest true to enable the Block editor" );
		}
	}
	
	/**
	 * Plugins may implement the gutenberg_can_edit_post_type filter so that we don't have to 
	 * We could possibly consider looking to see who's calling the shots.
	 */
	public function can_edit_post_type() {
		$this->can_edit = apply_filters( "gutenberg_can_edit_post_type", $this->can_edit, $this->post_type );
		 
		if ( $this->can_edit ) {
			return new oik_block_editor_opinion( 'A', false, 'T', "can_edit_post_type is enabled" );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'T', 'Block editor may not be used to edit the post type.' );
		}
	} 
	
	/**
	 * @TODO All the taxonomies need to be 'show_in_rest' for the Block editor to be available
	 */
	public function taxonomies_are_rest()  {
		// foreach ( taxonomy )
	 if ( true ) { 
			return new oik_block_editor_opinion( 'A', false, 'T', "Taxonomy is show_in_rest" );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'T', "Taxonomy is not show_in_rest" );
		}
	}


}
