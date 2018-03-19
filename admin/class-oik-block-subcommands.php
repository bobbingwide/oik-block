<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 * 
 * Parent class for oik_block_$level_subcommands
 * Provides access to oik_block_editor_opinions and oik_block_editor_opinion
 
 * status
 * reset
 * list
 * consider
 * decide
 * test 
 * convert
 *
 */
 
class oik_block_subcommands {

	public $level;
	public $post_type_or_id;
	
	
	function __construct( $level, $post_type_or_id ) {
		$this->level = $level;
		$this->post_type_or_id = $post_type_or_id;
	}
	
	public function status() {
		echo "Status not implemented";
	}
	
	public function reset() {
		gob();
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


