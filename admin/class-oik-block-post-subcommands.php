<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_post_subcommands extends oik_block_subcommands {

	function __construct( $level, $post_type_or_id ) {
		parent::__construct( $level, $post_type_or_id );
	}
	
	public function status() {
		//oik_block_opinions_site();
		$post = get_post( $this->post_type_or_id );
		if ( $post ) {
			$post_type = $post->post_type;
			if ( $post_type !== 'revision' ) {
				oik_block_opinions_post( $post, $post_type );
			} else {
				printf( 'Post ID %1$s is a revision', $post_id );
				echo PHP_EOL;
			}
		} else {
			echo "Post ID not found: $post_id" . PHP_EOL;
		}
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
