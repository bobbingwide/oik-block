<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class oik_block_editor_opinions {


	public $opinions = array();
	public $site_decision = null;
	public $post_type_decision = null;
	public $post_decision = null;
	public $user_decision = null;
	
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
	
	public function reset_opinions() {
		$this->opinions = array();
	}
	
	public function consider_site_opinions() {
		$this->site_decision = $this->consider_opinions( "AO" );
	}
	
	public function consider_post_type_opinions() {
		$this->post_type_decision = $this->consider_opinions( $this->site_decision );
	}
	
	public function consider_post_opinions() {
		$this->post_decision = $this->consider_opinions( $this->post_type_decision );
	}
	
	/**
	 * Considers the opinions to come to a decision
	 * 
	 * It shouldn't matter at which level the opinion is set
	 * but it does matter which opinion has precedence. 
	 * We'll consider the "opinion" as two chars combining the Editor and Mandatory fields
	 * Initial opinion is 'AO'. There are 36 combinations.
	 *
	 * 
	 */ 
	public function consider_opinions( $decision="AO") {
		foreach ( $this->opinions as $opinion ) {
			$decision = $opinion->consider( $decision ); 
		}
		return $decision;
	}
	
	/**
	 * 
	 */
	public function report_summary() {
		echo "Site decision: " .  $this->site_decision . PHP_EOL;
		if ( $this->post_type_decision ) {
			echo "Post type decision: " . $this->post_type_decision . PHP_EOL;
		}
		if ( $this->post_decision ) {
			echo "Post decision: " . $this->post_decision . PHP_EOL;
		}
		//$decision = $this->consider_opinions();
		//echo "Decision: $decision" . PHP_EOL;
	}
	
	/**
	 * Implements the decision of the considered opinions
	 *
	 * Do we need to fetch the current decision? 
	 *  
	 * @param object $post the post object that's been analysed in context
	 * 
	 
	 */
	public function implement_decision( $post ) {
		//$decision = $this->consider_opinions();
		$current_decision = $this->get_current_decision( $post );
		$this->update_decision( $post, $this->post_decision );
	}
	
	/**
	 * Fetch the current decision 
	 * 
	 * If not set then we'll need to determine it
	 * If it is, then we'll just re-determine it
	 */
	
	public function get_current_decision( $post ) {
		$decision = get_post_meta( $post->ID, "_oik_block_editor", true );
		if ( !$decision ) {
			$decision = "AO";
		}
		printf( 'Decision: %1$s %2$s', $post->ID, $decision ); 
		echo PHP_EOL;
		return $decision;
	}
	
	public function update_decision( $post, $decision ) {
		update_post_meta( $post->ID, "_oik_block_editor", $decision );
		printf( 'Updated decision: %1$s %2$s',  $post->ID,  $decision );
		echo PHP_EOL;
		
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
	
	public function gather_all_post_opinions( $post_type ) {
		$posts = $this->fetch_posts( $post_type );
		foreach ( $posts as $post ) {
			$this->reset_opinions();
			$this->gather_post_opinions( $post );
			$this->consider_post_opinions();
			$this->implement_decision( $post );
			
		}
	
	}
	
	
	/**
	 * Fetch all posts where post_meta is not set
	 */
	public function fetch_posts( $post_type ) {
	
		$args = array( "post_type" => $post_type
								, "post_status" => "any"
								, "posts_per_page" => -1
								, "meta_key" =>  "_oik_block_editor"
								, "meta_compare" => "NOT EXISTS" 
								, "meta_value" => "1"	 // ignored
								);
		$posts = get_posts( $args );
		printf( 'Analysing: %1$s %2$s', count( $posts), $post_type );
		echo PHP_EOL;
		
		return $posts;
	
	}

	
	
	/**
	 * This is (probably)  the routine to gather opinions for each individual post in the loop of posts
	 */ 
	public function gather_opinions( $post ) {
		echo $post->ID . PHP_EOL;
		
		$opinions = array();
		//$opinions[] = new oik_block_editor_opinion();
		$opinions = apply_filters( "oik_block_gather_post_opinions", $opinions, $post );
		
	$opinions->gather_post_opinions( $post );
	$opinions->consider_post_opinions();
	$opinions->report_summary();
	$opinions->report();
	$opinions->implement_decision( $post );
		
		
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
