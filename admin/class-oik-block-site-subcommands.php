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
	
	public function consider() {
		gob();
	}
	
	public function decide() {
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


