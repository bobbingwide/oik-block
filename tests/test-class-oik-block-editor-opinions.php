<?php 

/**
 * @copyright: Bobbing Wide 2018
 * @package: oik-block
 */
 
class Tests_class_oik_block_editor_opinions extends BW_UnitTestCase {

	function setUp() {
		parent::setUp();
		if ( !class_exists( "oik_block_editor_opinions" ) ) {
			oik_require( "admin/class-oik-block-editor-opinions.php", "oik-block" );
		}
	}
	
	function opinions() {
		$opinions = oik_block_editor_opinions::instance();
		return $opinions;
	}
	
	function test_construct() {
		$opinions = $this->opinions();
		$this->assertInstanceOf( "oik_block_editor_opinions", $opinions );
	}
	
	/**
	 * Test that we can add opinions
	 */
	function test_add_opinions() {
		///
	}
	
	function test_gather_site_opinions() {
		$opinions = $this->opinions();
		$opinions->gather_site_opinions();
		$opinions->report();
	}
		
	
	function test_consider_opinions() {
		$opinions = $this->opinions();
		$editor = $opinions->consider_opinions();
		$this->assertEquals( 'C', $editor );
	}
	
	function test_report() {
		$opinions = $this->opinions();
		$opinions->report();
	}
	
	 
} 
