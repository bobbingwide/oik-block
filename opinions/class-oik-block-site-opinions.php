<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_site_opinions {


	/**
	 * Editor | Mandatory | Level | 
	 * ------ | --------- | ----- | 
	 */
	private $thoughts = array( "WordPress_version" 
													 , "gutenberg_available" 
													 );

	public function __construct() {
		add_filter( "oik_block_site_opinions", $this->form_opinions, 10 );
	}	
	
	/**
	 *
	 */
	public function form_opinions( $opinions ) {
		bw_trace2();
		foreach ( $this->thoughts as $thought ) {
			$opinion = $this->$thought();
			if ( $opinion ) {
				$opinions[] = $opinion;
			}
		}
		bw_trace2();
		return $opinions;
	}
	
	public function WordPress_version() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.9", ">=" ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', "Block editor compatible" );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'S', 'WordPress version too low $wp_version', "Upgrade WordPress" );
		}
		
	} 
	
	
	public function gutenberg_available() {
		if ( function_exists(  "the_gutenberg_project" ) ) {
			return new oik_block_editor_opinion( 'A', false, "S", "Block editor available" );
		}
		else {
			return new oik_block_editor_opinion( 'C', true, 'S', "Block editor not available", "Install and activate gutenberg plugin" );
		}
	}


}
