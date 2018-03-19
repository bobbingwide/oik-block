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
		oik_block_opinions_post_type( $this->post_type_or_id );
	}
	
	
	public function reset() {
		
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
