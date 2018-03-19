<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_site_subcommands extends oik_block_subcommands {

	function __construct() {
	}
	
	public function status() {
		oik_block_opinions_site();
	}
	
	public function reset() {
	
			$opinions = oik_block_editor_opinions::instance();
			$opinions->reset_decision();
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

}


