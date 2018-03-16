<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class oik_block_editor_opinions {


	public $opinions = array();
	
	/**
	 * @var dependencies_cache the true instance
	 */
	private static $instance;
	
	/**
	 * Return a single instance of this class
	 *
	 * @return object 
	 */
	public static function instance() {
		if ( !isset( self::$instance ) && !( self::$instance instanceof self ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Constructor for the class
	 * 
	 * Loads oik_block_editor_opinion class then lets routines
	 * know it's time to prepare their opinions.
	 */
	public function __construct() {
		if ( !class_exists( "oik_block_editor_opinion" ) ) {	
			oik_require( "admin/class-oik-block-editor-opinion.php", "oik-block" );
		}
		$this->opinions = array();
		//echo "Calling oik_block_prepare_opinions" . PHP_EOL;
		do_action( "oik_block_prepare_opinions" );
		//echo "Called" . PHP_EOL;
	}
	
	/**
	 * Adds an array of opinions to the array of opinions
	 * 
	 * Does it need to create the opinion?
	 * 
	 * Simple concatentation using the array union operator doesn't work for unkeyed arrays
	 * So do we need to append each entry individually or is there another array function? 
	 */
	public function add_opinions( $opinions ) {
		bw_trace2();
		$this->opinions = array_merge( $this->opinions, $opinions );
		bw_trace2( $this->opinions, "opinions now", false );
	}
	
	/**
	 * Considers the opinions to come to a decision
	 * 
	 * 
	 */ 
	public function consider_opinions() {
		$decision = 'C';
		return $decision;
	}
	
	/**
	 * Gathers the opinions at Site level
	 * 
	 * This includes analysis of relevant 
	 * - plugins ( active and inactive ), mu-plugins
	 * - registered hooks
	 * - options
	 * - Multisite configuration
	 */
	public function gather_site_opinions() {
		$opinions = array();
		$opinions = apply_filters( "oik_block_gather_site_opinions", $opinions );
		$this->add_opinions( $opinions );
	}
	
	
	/**
	 * Gathers the opinions at Post type level
	 * 
	 * This includes analysis of relevant: 
	 * - post registration details
	 * - taxonomies
	 * - meta boxes
	 * - options
	 * - registered fields
	 */
	public function gather_post_type_opinions( $post_type ) {
		$opinions = array();
		$opinions = apply_filters( "oik_block_gather_type_opinions", $opinions, $post_type );
		$this->add_opinions( $opinions );
	}
	
	
	/**
	 * Gathers the opinions at Post level
	 * 
	 * This includes analysis of the:
	 * - post content
	 * - post meta data 
	 * - attachments 
	 */
	public function gather_post_opinions( $post ) {
		$opinions = array();
		//$opinions[] = new oik_block_editor_opinion();
		$opinions = apply_filters( "oik_block_gather_post_opinions", $opinions, $post );
		bw_trace2( $opinions, "opinions", false  );
		$this->add_opinions( $opinions );
	}
	
	public function gather_user_opinions( $user ) {
		$opinions = array();
		$opinions = apply_filters( "oik_block_gather_user_opinions", $opinions, $user );
		$this->add_opinions( $opinions );
	}

	
	
	/**
	 * This is (probably)  the routine to gather opinions for each indivindual post in the loop of posts
	 */ 
	public function gather_opinions( $post ) {
	}
	
	/**
	 * Produces a report of all the opinions
	 */
	public function report() {
		$this->report_header();
		foreach ( $this->opinions as $opinion ) {
			$opinion->report();
		}
		$this->report_footer();
	
	}
	
	/**
	 * @TODO - shared libary capability
	 */
	public function report_header() {
		if ( "cli" == php_sapi_name() ) {
			echo "Editor | Mandatory? | Level | Opinion | Notes " . PHP_EOL;
			echo "------ | ---------- | ----- | ------- | ----- " . PHP_EOL;
		} else {
			oik_require( "shortcodes/oik-table.php" );
			bw_table_header( bw_as_array( "Editor,Mandatory?,Level,Opinion,Notes") ); 
			stag( "tbody" );
		}	
	}
	
	/**
	 * @TODO - shared library capability
	 */
	public function report_footer() {
		if ( "cli" == php_sapi_name() ) {
			echo PHP_EOL;
		} else {
			etag( "tbody" );
			etag( "table" );
		}
	}



}



/**
 * Determines the compatibility of all posts in the post type
 * 
 * @param string $post_type
 * @return array( #Blocks, #Compatible, #Incompatible, #Unknown )
 */
function oik_block_posts_compatible( $post_type ) {
	$args = array( "post_type" => $post_type
							, "post_status" => "any"
							, "post_parent" => -1
							, "posts_per_page" => -1
							);
	$posts = bw_get_posts( $args );
	$compatibilities = array( "Blocks" => 0
													, "Compatible" => 0
													, "Incompatible" => 0
													, "Unknown" => 0
													);
	foreach ( $posts as $post ) {
		$compatible = oik_block_post_compatible( $post );
		$compatibilities[ $compatible ]++;
	}
	return $compatibilities;
}

function oik_block_post_compatible( $post ) {
 
	return "Blocks";
	 
}
