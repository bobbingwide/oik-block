<?php 


/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
 

 
class oik_block_hook_checker {

	public $opinions;				 

	function __construct() {
	$this->opinions = array();
	
	}
	
	function analyse() {
		$this->opinions[] = new oik_block_editor_opinion( "A", false, "S", "I'm thinking about hooks", "TBC" );
	}
	
	function get_opinions() {
		return $this->opinions;
	}

}
