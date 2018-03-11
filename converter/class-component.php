<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class component {

	public $component;
	public $type;
	public $path;
	public $count;
	public $author;
	public $third_party;
	public $tests;
	
	public function __construct( $component, $type, $path )  {
		$this->component = $component;
		$this->type = $type;
		$this->path = $path;
		$this->count = 0;
		$this->author = "";
		$this->third_party = true;
		$this->tests = false;
		//if ( $this->type == "plugins" ) {
			$this->is_author_bobbingwide();
		//}
		if ( $this->third_party ) {
			$this->set_third_party();
		}
		$this->set_tests();
		
	}
	
	public function add() {
		$this->count++;
	}
	
	/**
	 * Tests if it's a third party plugin
	 *
	 * - If this is one of our repo's it may not be a third party plugin or theme
	 * - If there is both a README.md and readme.txt
	 * - It would be better to determine who the contributors are	from get_plugin_data()
	 * 
	 * 
	 */
	function set_third_party() {
		$myrepo = "C:/github/bobbingwide/{$this->component}";
		if ( file_exists( $myrepo ) ) {
			if ( file_exists( "$myrepo/README.md" ) && file_exists( "$myrepo/readme.txt" ) ) {
				//print_r( $this );
				$this->third_party = false;
				//gob();
			}
		}
			
	}
	
	/**
	 * Sets third_party false if author is bobbingwide
	 */
	function is_author_bobbingwide() {
		$plugin = array();
		$plugin[] = $this->path;
		$plugin[] = $this->component;
		$plugin[] = $this->component . ".php";
		$plugin_file = implode( "/", $plugin );
		
		if ( file_exists( $plugin_file ) ) {
			$plugin_data = get_plugin_data( $plugin_file, false, false );
			if ( $plugin_data ) {
				$author = bw_array_get( $plugin_data, "Author", true );
				$this->author = str_replace( ",", "", $author );
			}
		}
	}
	
	function set_tests() {
		$test_dir ="{$this->path}/{$this->component}/tests";
		//echo $test_dir;
		if ( file_exists( $test_dir ) ) {
			$this->tests = true;
		}
	}
	
	function report() {
		$report = array();
		$report[] = $this->type;
		$report[] = $this->component;
		$report[] = $this->count;
		$report[] = $this->author;
		$report[] = $this->third_party;
		$report[] = $this->tests;
		$report[] = $this->path;
		$line = implode( ",", $report );
		echo $line . PHP_EOL;
		return $this->count;
	} 


}
