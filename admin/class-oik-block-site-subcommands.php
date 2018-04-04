<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_site_subcommands extends oik_block_subcommands {

	public $site_summary = null;

	function __construct() {
		oik_require( "admin\class-oik-block-site-summary.php", "oik-block" );
		$this->site_summary = new oik_block_site_summary();
	}
	
	public function status() {
		$this->oik_block_opinions_site();
	}
	
	public function reset() {
		$this->site_summary->reset_decisions();
	}	

	public function list() {
		echo __FUNCTION__ . "not implemented";
	}
	
	public function decide() {
		oik_require( "shortcodes/oik-content.php", "oik-block" );
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			$post_type_object = get_post_type_object( $post_type );
			$editor = oik_block_post_type_compatible( $post_type, $post_type_object );
			if ( $editor == "Block" ) {
				oik_block_opinions_subcommand( "decide", "type", $post_type );
			}
		}
	}
	
	public function consider() {
		gob();
	}
	
	public function test() {
		gob();
	}
	
	public function convert() {
		gob();
	}

	function oik_block_opinions_site() {

		$opinions = oik_block_editor_opinions::instance();
		$opinions->gather_site_opinions();
		$opinions->consider_site_opinions();
		$opinions->report();
	
		$opinions->report_summary();
	
		$this->site_summary->report();
		$this->site_summary->gut_feel();

	}

}


