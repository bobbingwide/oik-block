<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2023
 * @package oik-block
 */
class Tests_load_shortcodes extends BW_UnitTestCase {

	/**
	 * set up logic
	 *
	 * - ensure any database updates are rolled back
	 */
	function setUp(): void {
		parent::setUp();
	}

	/**
	 * Tests that the shortcode php files load correctly
	 * @return void
	 */
	function test_load_shortcodes() {
		oik_require( 'shortcodes/oik-content.php', 'oik-block');
		oik_require( 'shortcodes/oik-contents.php', 'oik-block');
		oik_require( 'shortcodes/oik-guts.php', 'oik-block');
		$this->assertTrue( true );

	}

	function test_load_libs() {
		oik_require( 'libs/bwtrace.php', 'oik-block');
		oik_require( 'libs/class-BW-table.php', 'oik-block');
		oik_require( 'libs/oik-depends.php', 'oik-block');
		oik_require( 'libs/oik_boot.php', 'oik-block');
		$this->assertTrue( true );
	}

}