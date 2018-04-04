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
				$this->oik_block_opinions_post( $post, $post_type );
			} else {
				printf( 'Post ID %1$s is a revision', $post_id );
				echo PHP_EOL;
			}
		} else {
			echo "Post ID not found: $post_id" . PHP_EOL;
		}
	}
	
	public function get_valid_post() {
		$post = get_post( $this->post_type_or_id );
		if ( $post ) {
			$post_type = $post->post_type;
			if ( $post_type === 'revision' ) {
				printf( 'Post ID %1$s is a revision', $post_id );
				echo PHP_EOL;
			}
		} else {
			echo "Post ID not found: $post_id" . PHP_EOL;
		}
		return $post;
	}
		
	
	
	public function reset() {
		
	}

	public function list() {
		echo __FUNCTION__ . "not implemented";
	}
	
	public function consider() {
		$post = $this->get_valid_post();
		$opinions = oik_block_editor_opinions::instance();
		$opinions->gather_post_opinions( $post );
		$opinions->consider_post_opinions();
		$opinions->report();
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
	
	


	/**
	 * Gather the opinions for the selected post
	 */
	function oik_block_opinions_post( $post, $post_type ) {
		echo "Post: {$post->ID}. Type: $post_type" . PHP_EOL;

		$opinions = oik_block_editor_opinions::instance();
		$opinions->gather_site_opinions();
		$opinions->consider_site_opinions();
		$opinions->reset_opinions();
		$opinions->gather_post_type_opinions( $post_type );
		$opinions->consider_post_type_opinions();
		$opinions->reset_opinions();
		$opinions->gather_post_opinions( $post );
		$opinions->consider_post_opinions();
		$opinions->report_summary();
		$opinions->report();
		$opinions->implement_decision( $post );
	}

}
