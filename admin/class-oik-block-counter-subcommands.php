<?php

class oik_block_counter_subcommands {

	private $subcommand = null;
	private $post_type_or_id = null;

	private $blocks = [];
	private $blocklist = [];



	function __construct( $subcommand='count', $post_type_or_id='block' ) {
		$this->subcommand = $subcommand;
		$this->post_type_or_id = $post_type_or_id;
		$this->get_posts( $this->post_type_or_id );
		$this->count_all_blocks();
		$this->report();




	}

	function get_posts( $post_type_or_id ) {
		oik_require( 'includes/bw_posts.php');
		if ( is_numeric( $post_type_or_id ) ) {
			$post = get_post( $post_type_or_id );
			$this->posts = [ $post ];
		} else {
			$args = [ 'numberposts' => - 1
		            , 'post_type' => $post_type_or_id
					];
			$this->posts = bw_get_posts( $args );
		}


	}

	function count_all_blocks() {
		$this->echo( 'Processing: ', count( $this->posts ) );
		foreach ( $this->posts as $post ) {
			$this->count_blocks( $post );
		}
	}

	function count_blocks( $post ) {
		$this->ID = $post->ID;
		$this->post = $post;
		$has_blocks = has_blocks( $post->post_content );
		if ( $has_blocks ) {
			$blocks = parse_blocks( $post->post_content );
			$this->count_block_names( $blocks );

		}


	}

	function count_block_names( $blocks ) {
		//print_r( $blocks );

		foreach ( $blocks as $block ) {
			$this->count_block( $block );

			if ( isset( $block['innerBlocks'] ) && count( $block['innerBlocks']) ) {
				//print_r( $block['innerBlocks']);

				foreach ( $block['innerBlocks'] as $innerBlock ) {
					//print_r( $innerBlock );
					$this->count_block_names( [ $innerBlock ] );
				}

			}
		}

	}

	function count_block( $block ) {
		//print_r( $block );
		$block_name = $block['blockName'];
		/*
		echo '?';
		echo $block_name;
		echo ',';
		echo strlen( $block['innerHTML']);
		echo $block['innerHTML'];
		echo '!';
		echo PHP_EOL;
		*/

		if ( null === $block_name ) {
		     if ( strlen( trim( $block['innerHTML'] ) ) > 2  ) {
			     $block_name='core/freeform';
		     } else {

				return;
			}
		}
		//echo "!$block_name!";
		//gob();
		if ( !isset( $this->blocks[ $block_name] ) ) {
			$this->blocks[ $block_name] = 0;
		}
		$this->blocks[ $block_name] += 1;
		$this->add_block( $block_name );
	}

	function add_block( $block_name ) {
		$block_prefix = $this->get_prefix( $block_name );
		$this->blocklist[] = [ $block_name, $this->ID, $block_prefix, $this->post->post_type ];
	}

	function get_prefix( $block_name ) {
		$this->echo( 'Block name: ', $block_name );
		$block_name_parts = explode( '/', $block_name );
		return $block_name_parts[0];
	}

	function report() {

		print_r( $this->blocklist );
		print_r( $this->blocks );

		$this->echo( 'Post type or ID:', $this->post_type_or_id );
		$this->echo( 'Processed: ', count( $this->posts ) );
		$this->echo( "Block types: ", count( $this->blocks ) );
		$this->report_block_type_counts();
		$this->echo( 'Blocks: ', count( $this->blocklist ) );

	}

	function echo( $text, $value ) {
		echo $text;
		echo $value;
		echo PHP_EOL;
	}

	function report_block_type_counts() {
		arsort( $this->blocks);
		print_r( $this->blocks );

	}


}
