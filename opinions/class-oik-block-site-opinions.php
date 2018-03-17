<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_site_opinions {


	/**
	 * Array of Site level thoughts
	 */
	private $thoughts = array( "WordPress_version" 
													 , "gutenberg_available" 
													 , "mu_plugin_support"
													 , "active_network_plugin_support"
													 , "active_plugin_support"
													 );

	public function __construct() {
		echo __CLASS__ . PHP_EOL;
		add_filter( "oik_block_gather_site_opinions", array( $this, "form_opinions" ), 10 );
	}	
	
	/**
	 * Forms the opinions for this class
	 *
	 * This method could be in a higher level class
	 * 
	 */
	public function form_opinions( $opinions ) {
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
		//bw_trace2();
		return $opinions;
	}
	
	public function WordPress_version() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.9", ">=" ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', "WordPress $wp_version is Block editor compatible." );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'S', 'WordPress version too low: $wp_version', "Upgrade WordPress" );
		}
		
	}
	
	/**
	 * @TODO Check the WordPress version once something has been committed to core. 
	 */
	public function gutenberg_available() {
		if ( function_exists(  "the_gutenberg_project" ) ) {
			return new oik_block_editor_opinion( 'A', false, "S", "Block editor available" );
		}
		else {
			return new oik_block_editor_opinion( 'C', true, 'S', "Block editor not available", "Install and activate gutenberg plugin" );
		}
	}
	
	/**
	 * It may not be possible to determine tested up to for mu-plugins
	 * so all we can do is comment on their existence
	 */
	
	public function mu_plugin_support() {
		$mu_plugins = wp_get_mu_plugins();
		if ( count( $mu_plugins ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', 'Site has Must Use plugins' );
		} else {
			//
		}
	
	}
	
	/**
	 * 
	 */
	
	public function active_network_plugin_support() {
	
	}
	
	/**
	 * Looks at "Requires at least" and "Tested up to" from the readme.txt file for the plugin
	 *
	 * If Requires at least is > 4.9 then that's great
	 * If Tested up to is > 4.9 then that's great
	 * If it's >= 5.0 then that's even better
	 * 
	 */
	public function active_plugin_support() {
	}


}
