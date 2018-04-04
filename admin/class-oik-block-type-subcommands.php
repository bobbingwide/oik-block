<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_type_subcommands extends oik_block_subcommands {

	function __construct( $level, $post_type_or_id ) {
		parent::__construct( $level, $post_type_or_id );
	}
	
	public function status() {
		$this->oik_block_opinions_post_type( $this->post_type_or_id );
	}
	
	
	public function reset() {
	
		
	}

	public function list() {
		echo __FUNCTION__ . "not implemented";
	}
	
	public function consider() {
		$opinions = oik_block_editor_opinions::instance();
		
		$opinions->gather_site_opinions();
		$opinions->consider_site_opinions();
		
		$opinions->gather_post_type_opinions( $this->post_type_or_id );
		$opinions->consider_post_type_opinions();
	}
	
	public function decide() {
		$this->consider();
	
		$opinions = oik_block_editor_opinions::instance();
		$opinions->gather_all_post_opinions( $this->post_type_or_id );
		
	}
	
	public function test() {
		gob();
	}
	
	public function convert() {
		gob();
	}
	
	

	/**
	 * Gathers the opinions for each unopinionated post for the post type
	 *
	 * @param string $post_type 
	 */
	function oik_block_opinions_post_type( $post_type ) {
		$opinions = oik_block_editor_opinions::instance();
		$opinions->gather_site_opinions();
		$opinions->consider_site_opinions();
		$opinions->report();
		$opinions->reset_opinions();
		$opinions->gather_post_type_opinions( $post_type );
		$opinions->consider_post_type_opinions();
		$opinions->report();
		$opinions->reset_opinions();
		$opinions->report_summary();
	
		$opinions->gather_all_post_opinions( $post_type );
	}

}
