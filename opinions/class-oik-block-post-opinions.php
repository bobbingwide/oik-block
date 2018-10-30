<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_post_opinions {

	/**
	 * Array of methods to invoke when gathering our thoughts and forming opinions.
	 *
	 */
	private $thoughts = array( "gutenberg_activated"
													 , "gutenberg_content_has_blocks"
													 , "gutenberg_content_has_dynamic_blocks"
													 , "content_has_shortcodes"
													 , "content_has_inline_shortcodes" 
													 , "content_has_incompatible_shortcodes"
													 );
													 
	public $post = null; 
	public $post_content = null; 	
	
	public $level = 'P'; // Post opinions	
	
	/**
	 * Array of considered thoughts that are true.
	 * - Set using $this->considered_true( __FUNCTION__ );
	 * - Queried using $this->considered( "thought" ) - where "thought" is a valid thought.
	 * - If the "thought" is not valid we'll get told quick enough
	 */
	private $considered_thoughts = array();
	
	public function __construct() {
		//echo __CLASS__ . PHP_EOL;
		$this->post = null;
		$this->post_content = null;
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
	 * Determines the result of a previous thought
	 */
	public function considered( $thought ) {
		$considered_thought = bw_array_get( $this->considered_thoughts, $thought, false );
		if ( !$considered_thought ) {	
			if ( !method_exists( $this, $thought ) ) {
				echo "Invalid thought: $thought" . PHP_EOL;
				die();
			}
		}
		return $considered_thought;
	}
	
	/**
	 * Records a thought as considered to be true
	 */
	public function considered_true( $thought ) {
		$this->considered_thoughts[ $thought ] = true;
	}
	
	/**
	 * Determines if Gutenberg is activated
	 *
	 */
  public function gutenberg_activated() {
		// Gutenberg probably doesn't need to be activated! 
    //if ( function_exists( "gutenberg_content_has_blocks" ) && function_exists( "get_dynamic_block_names" ) ) {
		if ( function_exists( "the_gutenberg_project" ) ) {
			
			$this->considered_true( __FUNCTION__ );
		}	else {
			if ( function_exists( "has_blocks" ) ) {
			
				$this->considered_true( __FUNCTION__ );
				//return new oik_block_editor_opinion( 'B', false, $this->level, "Gutenberg built into core" );
				
			} else {
				return new oik_block_editor_opinion( 'C', false, $this->level, "Gutenberg not activated" );
			}
		}
	}	
	
	/**
	 * Checks content for Gutenberg blocks
	 * 
	 */
	public function gutenberg_content_has_blocks() {
		if ( $this->considered( "gutenberg_activated" ) ) {
			if ( has_blocks( $this->post_content ) ) {
				$this->considered_true( __FUNCTION__ );
				return new oik_block_editor_opinion( 'B', false, $this->level, "Content already contains blocks" );
			} else {
				
				return new oik_block_editor_opinion( 'A', false, $this->level, "Content does not contain blocks" );
			}
		} 		
	}
	
	/**
	 * 
	 * @TODO Incomplete
	 */
	public function gutenberg_content_has_dynamic_blocks() {
		if ( $this->considered( "gutenberg_activated" ) && $this->considered( "gutenberg_content_has_blocks" ) ) {
			$dynamic_blocks = get_dynamic_block_names();
			
			
		}
	}
	
	/**
	 *
	 */ 
	public function content_has_shortcodes() {
		if ( false === strpos( $this->post_content, '[' ) ) {
			return new oik_block_editor_opinion( 'A', false, $this->level, "Content does not contain shortcodes" );
		} else {
			$this->considered_true( __FUNCTION__ );
		}
	}
	
	/**
	 * Determines if the content has inline shortcodes that should not be converted to a core/shortcode block
	 *
	 * If the post already has blocks then we don't bother ourselves as this is considered OK
	 * If it doesn't, and it has inline shortcodes then our opinion is 'CM' - even though we actually 
	 * need to use the Block editor to perform the conversion. 
	 * Seems a bit silly, but it's my gut feel I'm trying to evaluate and this is the easiest way of doing it.
	 */
	public function content_has_inline_shortcodes() {
		if ( $this->considered( "content_has_shortcodes" ) &&
				!$this->considered( "gutenberg_content_has_blocks" ) ) {
			//echo __FUNCTION__;
			$inline_shortcodes = $this->query_inline_shortcodes();
			//print_r( $inline_shortcodes );
			$count = $this->count_shortcodes( $inline_shortcodes );
			if ( $count ) {
				$this->considered_true( __FUNCTION__ );
				return new oik_block_editor_opinion( 'C', true, $this->level, sprintf( 'Inline shortcodes found. Count: %1$s', $count ), "Manual conversion required" ); 
			}  
		}
	}
	
	private function count_shortcodes( $shortcodes ) {
		$count = 0;
		foreach ( $shortcodes as $shortcode ) {
			$shortcode_present = $this->content_has_shortcode( $shortcode );	
			if ( $shortcode_present ) {
				$count++;
			}
		}
		return $count;
	}
	
	
	/**
	 * Search post_content for the shortcode with no parameters or with at least one
	 *
	 * We have to allow for similar shortcodes e.g. wp and wpms 
	 */
	private function content_has_shortcode( $shortcode ) {
		$pos = strpos( $this->post_content, "[" . $shortcode . "]" );
		if ( $pos === false ) {
			$pos = strpos( $this->post_content, "[" . $shortcode . " " );
		}
		return $pos !== false; 
	}
	
	
	public function query_inline_shortcodes() {
		$inline_shortcodes = array();
		$inline_shortcodes = apply_filters( "oik_block_query_inline_shortcodes", $inline_shortcodes );
		return $inline_shortcodes;
	}
	
	public function query_incompatible_shortcodes() {
		$incompatible_shortcodes = array();
		$incompatible_shortcodes = apply_filters( "oik_block_query_incompatible_shortcodes", $incompatible_shortcodes );
		return $incompatible_shortcodes;
	} 
	
	
	/**
	 * Determines if the content has incompatible shortcodes
	 *
	 * Where the incompatibility is with the core/shortcode block.
	 * And the solution is to convert it to a different block, that may be a custom block if core doesn't provide it.
	 *
	 */
	public function content_has_incompatible_shortcodes() {	
		if ( $this->considered( "content_has_shortcodes" ) &&
				!$this->considered( "gutenberg_content_has_blocks" ) ) {
			//echo __FUNCTION__;
			$incompatible_shortcodes = $this->query_incompatible_shortcodes();
			$count = $this->count_shortcodes( $incompatible_shortcodes );
			if ( $count ) {
				$this->considered_true( __FUNCTION__ );
				return new oik_block_editor_opinion( 'C', true, $this->level, sprintf( 'Incompatible shortcodes found. Count: %1$s', $count ), "Manual conversion required" ); 
			}
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
	public function do_we_need_this() {
	
	 
			
			return apply_filters( "oik_block_" . __FUNCTION__ , null, $post->post_content ); 
		
	} 
	


}
