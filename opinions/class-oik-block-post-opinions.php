<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_post_opinions {


	/**
	 * Array of methods to invoke to when gathering our thoughts and forming opinions.
	 */
	private $thoughts = array( "gutenberg_content_has_blocks"
													 );
	public $post = null; 
	public $post_content = null; 	
	
	public $level = 'P'; // Post opinions								

	public function __construct() {
		echo __CLASS__ . PHP_EOL;
		$this->post = null;
		$this->post_content = null;
		add_filter( "oik_block_gather_post_opinions", array( $this, "form_opinions" ), 10, 2 );
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
	 */
	public function gutenberg_content_has_blocks() {
		if ( function_exists( "gutenberg_content_has_blocks" )  && gutenberg_content_has_blocks( $this->post_content ) ) {
			return new oik_block_editor_opinion( 'B', false, $this->level, "Content already contains blocks" );
		} else {
			return new oik_block_editor_opinion( 'A', false, $this->level, "Content does not contain blocks" );
		}
	}
	


}
