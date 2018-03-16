<?php 

/**
 * @copyright: Bobbing Wide 2018
 * @package: oik-block
 */
 
class Tests_class_oik_block_editor_opinion extends BW_UnitTestCase {

	function setUp() {
		parent::setUp();
		if ( !class_exists( "oik_block_editor_opinion" ) ) {
			oik_require( "admin/class-oik-block-editor-opinion.php", "oik-block" );
		}
	}
	
	function create_opinion() {
		$opinion = new oik_block_editor_opinion();
		return $opinion;
	}
	
	
	function test_construct() {
		$opinion = $this->create_opinion();
		$this->assertInstanceOf( "oik_block_editor_opinion", $opinion );
	
	}
	
	function test_report() {
		$opinion = $this->create_opinion();
		$opinion->report();
	}
	
	function test_set_preferred_editor() {
		$opinion = $this->create_opinion();
		$opinion->set_preferred_editor( "A" );
		$preferred = $opinion->get_preferred_editor();
		$this->assertEquals( "A", $preferred );
	} 
	 
} 
