<?php 

/** 
 * 
 * File not included in the PHP Unit test suite:
 * 
 * - File name contains underscores not hyphens - see phpunit.xml.dist
 * - Logic is incomplete anyway!
 */

class Tests_hooks_for_the_content extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
	}
	
	/**
	 * Tests for:
	 * <!--more-->
	 * <!--nextpage-->
	 * <!--noteaser-->
	 *
	 * Is it possible to have a page that validly contains multiple pages and more's
	 * and how would you use it? 
	 */
	function test_hooks_for_the_content() {
		global $wp_filter;
		//print_r( $wp_filters );
		$the_content = $wp_filter[ 'the_content' ];
		print_r( $the_content ); 
		bw_trace_attached_hooks( 'the_content' ); 
		
		$hooks = bw_trace_get_attached_hooks( 'the_content' );
		print_r( $hooks );
		gob();
	
	
	}
	
	function test_hooks_for_the_content_gutenberg() {
	
	}
	
}
