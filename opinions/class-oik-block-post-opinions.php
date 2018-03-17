<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_post_opinions {


	/**
	 * Array of methods to invoke when gathering our thoughts and forming opinions.
	 *
	 * Could potentially be used as true/false values rather than having multiple fields
	 * e.g. if $this->test( "gutenberg_activated" )
	 * 
	 */
	private $thoughts = array( "gutenberg_activated"
													 , "gutenberg_content_has_blocks"
													 , "gutenberg_content_has_dynamic_blocks"
													 , "content_has_shortcodes"
													 );
	public $post = null; 
	public $post_content = null; 	
	
	public $level = 'P'; // Post opinions	
	
	public $gutenberg_activated = false;
	public $content_has_blocks = false;	
	public $content_has_shortcodes = false;						

	public function __construct() {
		echo __CLASS__ . PHP_EOL;
		$this->post = null;
		$this->post_content = null;
		$this->gutenberg_activated = false;
		$this->content_has_blocks = false;
		add_filter( "oik_block_gather_post_opinions", array( $this, "form_opinions" ), 1, 2 );
	}	
	
	/**
	 * Forms the opinions for this class
	 * 
	 * @param array $opinions
	 * @param string $post
	 * @return array $opinions
	 */
	public function form_opinions( $opinions, $post ) {
		$this->post = $post;
		$this->post_content = $post->post_content;
		
		//bw_trace2();
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
	 * get_dynamic_block_names
	 */
  public function gutenberg_activated() {
		if ( function_exists( "gutenberg_content_has_blocks" )  && function_exists( "get_dynamic_block_names" ) ) {
			$this->gutenberg_activated = true;
		}	else {
			return new oik_block_editor_opinion( 'C', false, $this->level, "Gutenberg not activated" );
		}
	}	
	
	/**
	 */
	public function gutenberg_content_has_blocks() {
		if ( $this->gutenberg_activated ) {
			if ( gutenberg_content_has_blocks( $this->post_content ) ) {
				$this->content_has_blocks = true;
				return new oik_block_editor_opinion( 'B', false, $this->level, "Content already contains blocks" );
			} else {
				
				return new oik_block_editor_opinion( 'A', false, $this->level, "Content does not contain blocks" );
			}
		} 		
	}
	
	public function gutenberg_content_has_dynamic_blocks() {
		if ( $this->gutenberg_activated && $this->content_has_blocks ) {
			$dynamic_blocks = get_dynamic_block_names();
			
		}
	}
	
	public function content_has_shortcodes() {
		if ( false === strpos( $this->post_content, '[' ) ) {
			return new oik_block_editor_opinion( 'A', false, $this->level, "Content does not contain shortcodes" );
		} else {
			$this->content_has_shortcodes = true;
		}
	}
	
	/**
	 * Here we extend the filtering to perform a specific test
	 * 
	 * So a plugin can work with greater granularity.
	 * oik_block_content_has_incompatible_shortcodes
	 * rather than
	 * oik_block_gather_post_options
	 */
	
	public function content_has_incompatible_shortcodes() {	
		if ( $this->test( "content_has_shortcodes" ) ) {
			return apply_filters( "oik_block_" . __FUNCTION__ , null, $post->post_content ); 
		}
	} 
	


}
