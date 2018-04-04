<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_type_opinions {


	/**
	 * Array of methods to invoke to when gathering our thoughts and forming opinions.
	 */
	private $thoughts = array( "show_UI"
													 , "show_in_rest"
													 , "supports_revisions"
													 , "supports_editor"
													 , "can_edit_post_type" 
													 , "taxonomies_are_rest" 
													 , "taxonomy_term_count_reasonable"
													 , "author_count_reasonable"
													 );
	public $post_type = null;		
	public $post_type_object = null;	 
	public $can_edit = true; 	
	
	public $level = 'T'; // Post type opinions								

	public function __construct() {
		//echo __CLASS__ . PHP_EOL;
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
	
	public function show_UI() {
		if ( $this->post_type_object->show_ui ) {
			return null; 
		} else {
			return new oik_block_editor_opinion( 'A', false, $this->level, "show_ui is true" );
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
	
	public function supports_revisions() {
		if ( post_type_supports( $this->post_type, 'revisions' ) ) {
			return null;
		} else {
			return new oik_block_editor_opinion( 'C', false, $this->level, "Post type does not support 'revisions'" );
		}
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
	 * All the taxonomies need to be 'show_in_rest' for the Block editor to be available
	 * 
	 * To cater for the post_format taxonomy ( which is not show_in_rest ), we also check that the taxonomy has show_ui.
	 *
	 * @TODO This routine stops checking when it finds one taxonomy that is not show in rest. 
	 * It should consider each taxonomy so that it can report on each one. 
	 *
	 */
	public function taxonomies_are_rest()  {
		$taxonomies = get_object_taxonomies( $this->post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );
			//bw_trace2( $taxonomy_object, "taxonomy_object", false );
			if ( $taxonomy_object->show_ui && !( $taxonomy_object->show_in_rest) ) { 
				return new oik_block_editor_opinion( 'C', true, 'T', sprintf( 'Taxonomy %1$s is not show_in_rest', $taxonomy ) );
			}	
		}
		return new oik_block_editor_opinion( 'A', false, 'T', "Taxonomies are show_in_rest" );
	}
	
	/** 
	 * The REST API is limited to querying 100 terms at a time.
	 * If there are more than 100 terms it's better to use the Classic Editor
	 *
	 * Count terms for all taxonomies, even if show_in_rest is false.
	 */
	public function taxonomy_term_count_reasonable() {
		$taxonomies = get_object_taxonomies( $this->post_type );
		$counts = array();
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( array( "taxonomy" => $taxonomy, "hide_empty" => false ) );
			$count = count( $terms );
			if ( $count > 100 ) { 
				return new oik_block_editor_opinion( 'C', true, 'T', sprintf( 'Taxonomy %1$s has too many terms: %2$d', $taxonomy, $count ) );
			}	
			$counts[] = $count;
		}
		return new oik_block_editor_opinion( 'A', false, 'T', sprintf( 'Taxonomies term counts are reasonable: %1$s', implode( ",", $counts ) ) );
	}
	
	/**
	 * Note: wp_list_authors() doesn't cater for user's with commas in their name!
	 * @TODO We should use get_users() instead.
	 */
	public function author_count_reasonable() {
		$author_supported = post_type_supports( $this->post_type, "author" );
		if ( $author_supported ) {
			$args = array( "echo" => false, "style" => 'csv', "html" => false, "hide_empty" => false, "show_fullname" => true );
			$authors = wp_list_authors( $args );
			$author_array = explode( ",", $authors );
			//print_r( $author_array );
			$count = count( $author_array );
			if ( $count > 100 ) {
				return new oik_block_editor_opinion( 'C', true, 'T', sprintf( 'Too many authors: %1$d', $count ) );
			} else {
				return new oik_block_editor_opinion( 'A', false, 'T', sprintf( 'Author counts are reasonable: %1$d', $count ) );
			}
		}
	}	


}
