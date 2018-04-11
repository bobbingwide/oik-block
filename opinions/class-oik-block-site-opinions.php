<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
 
class oik_block_site_opinions {


	/**
	 * Array of Site level thoughts
	 */
	private $thoughts = array( "WordPress_version" 
													 , "gutenberg_available" 
													 , "mu_plugin_support"
													 , "active_network_plugin_support"
													 , "active_plugin_support"
													 );

	public function __construct() {
		//echo __CLASS__ . PHP_EOL;
		add_filter( "oik_block_gather_site_opinions", array( $this, "form_opinions" ), 1 );
	}	
	
	/**
	 * Forms the opinions for this class
	 *
	 * This method could be in a higher level class
	 * 
	 */
	public function form_opinions( $opinions ) {
		//bw_trace2();
		foreach ( $this->thoughts as $thought ) {
		 if ( method_exists( $this, $thought ) ) {
				$opinion = $this->$thought();
			} else {
				gob();
			}
			 
			 
			if ( $opinion ) {
				$opinions[] = $opinion;
			}
		}
		//bw_trace2();
		return $opinions;
	}
	
	public function WordPress_version() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.9", ">=" ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', sprintf( 'WordPress %1$s is Block editor compatible.', $wp_version ) );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'S', sprintf( 'WordPress version too low: %1$s', $wp_version ), "Upgrade WordPress" );
		}
		
	}
	
	/**
	 * @TODO Check the WordPress version once something has been committed to core. 
	 */
	public function gutenberg_available() {
		if ( function_exists(  "the_gutenberg_project" ) ) {
			return new oik_block_editor_opinion( 'A', false, "S", "Block editor available" );
		}
		else {
			return new oik_block_editor_opinion( 'C', true, 'S', "Block editor not available", "Install and activate gutenberg plugin" );
		}
	}
	
	/**
	 * It may not be possible to determine tested up to for mu-plugins
	 * so all we can do is comment on their existence
	 */
	
	public function mu_plugin_support() {
		$mu_plugins = wp_get_mu_plugins();
		if ( count( $mu_plugins ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', 'Site has Must Use plugins' );
		} else {
			//
		}
	
	}
	
	/**
	 * 
	 */
	
	public function active_network_plugin_support() {
	
	}
	
	/**
	 * Looks at "Tested up to:" and "Gutenberg compatible:" from the readme.txt file for each plugin
	 * 
	 *
	 * @TODO Decide if "Requires at least" is a valid thing to test
	 
	 * My initial thought was we could use existing values. But further reading up on the purpose 
	 * of this tag suggests it's not a good test right now. Perhaps in the future we can make the inferences.
	 * - If Requires at least is > 4.9 then that's probably OK
	 * - If Requires at least is >= 5.0 then it implies it needs the block editor?
	 * 
	 * 
	 */
	public function active_plugin_support() {
		$this->load_plugincompatibility();
		//gob();
		$plugins = bw_get_active_plugins();
		//print_r( $plugins );
		foreach ( $plugins as $plugin_name => $plugin_file ) {
			//echo $plugin_name;
			$gc_opinion = $this->check_gutenberg_compatible( $plugin_name );
			
			$readme = $this->readme( $plugin_name );
			//$this->check_requires( $readme, "ge", "4.9" );
			$tut_opinion = $this->check_tested_up_to( $readme );
			$this->accumulate_plugin_opinions( $plugin_name, $gc_opinion, $tut_opinion );
			
		}
	}
	
	function accumulate_plugin_opinions( $plugin, $gc_opinion, $tut_opinion ) {
		echo "$plugin: $gc_opinion $tut_opinion" . PHP_EOL;
		
	}
	
	private function readme( $plugin_name ) {
		$file = WP_PLUGIN_DIR . '/'. $plugin_name . '/' . 'readme.txt';
		if ( file_exists( $file ) ) { 
			$lines = file( $file );
		} else {
			$lines = array();
			echo "No readme" . $file;
		}
		//print_r( $lines );
		return $lines;
	}
	
	/**
	 * Gets the value for the specified section in the readme.txt file
	 *
	 * Assumes a single line is needed
	 * @param string $needle Section name, incl. the colon
	 * @return string the trimmed value, if found
	 */
	public function get_value( $needle, $lines ) {
		$value = null;
		//echo count( $lines );
		foreach ( $lines as $line ) {
			//echo $line;
			$pos = strpos( $line, $needle );
			if ( 0 === $pos ) {
				$value = substr( $line, strlen( $needle ) );
				$value = trim( $value );
				break;
				//echo $value;
			}
		}
		return $value;
	}
	
	
	private function check_requires( $readme, $compare, $version ) {
		$value = $this->get_value( "Requires at least:", $readme );
		echo $value;
		$result = version_compare( $value, $compare, $version );
		echo $result;
	}
	
	/**
	 * Returns the opinion for "Tested up to:"
	 *
	 * - If Tested up to is > 4.9 then that's great - infer Likely-yes -> AO
	 * - If it's >= 5.0 then that's even better	- infer yes => AO
	 *
	 * Do we need to know how many likelies there are? 
	 */
	private function check_tested_up_to( $readme ) {
		$value = $this->get_value( "Tested up to:", $readme );
		//echo $value;
		//echo $version;
		//echo $compare;
		$result = version_compare( $value, "4.9", "ge" );
		if ( $result ) {
			$opinion = "AO";
		} else {
			$opinion = "CO";
		}
		return $opinion;
		
	}
	
	/**
	 * Determines Gutenberg compatibility 
	 *
   * Plugin evaluation, performed at Site level, will attempt to obtain the `Gutenberg compatible:` status 
	 * for each active plugin and accumulate the opinions into a single Mandatory opinion, 
	 * which will then be used to influence the overall decision.  
	 * 
	 The plugin compatibility database (  can be exported in three forms: CSV, JSON and tab separated
	 We can load up the table, from opinions/plugincompatibility.csv, 
   convert to an array and then index it for the required setting.
	  
	 If it's available we'll consider using this value.
	 
	 
	 Daniel Bachhuber's compatibility database suggests multiple values for the Gutenberg compatible setting, 
	 which we'll map to Opinions as below
	 
	*
	* Gutenberg compatible | Opinion 
	* -- | --
	* No | CO
	* Yes | AO
	* Likely-no | CO
	* Likely-yes | AO
	* Testing | AO
	* Unknown | AO

	*/
	public function check_gutenberg_compatible( $plugin ) {
		$gutenberg_compatible = $this->get_gutenberg_compatible( $plugin );
		if ( $gutenberg_compatible ) {
			$mapping = $this->map_gutenberg_compatible_to_opinion( $gutenberg_compatible );
		} else {
			$mapping = null;
		}
		return $mapping;
	}
	
	
	public function get_gutenberg_compatible( $plugin ) {
		$gutenberg_compatible = bw_array_get( $this->plugin_compatibility, $plugin, null );
		return $gutenberg_compatible;
	}
	
	
	public function map_gutenberg_compatible_to_opinion( $gutenberg_compatible ) {
		$gc = strtolower( $gutenberg_compatible );
		$mapping = array( "no" => "CO" 
										, "yes" => "AO"
										, "likely_no" => "CO"
										, "likely_yes" => "AO"
										, "testing" => "AO"
										, "unknown" => "AO"
										);
		$opinion = bw_array_get( $mapping, $gc, null );
		return $opinion;
	}								
									
	
	/**
	 * Parses the plugincompatibility.csv file
	 * 
	 * `
	 * »¿"Plugin","Gutenberg-Compatible","Compatibility Reason","Active Installs"
	 * "001-prime-strategy-translate-accelerator","unknown","","10000"
	 * "1-click-retweetsharelike","unknown","","3000"
	 * ...
	 * `
	 * 
	 * Note: There can be duplicates; we'll take the last and hope for the best.
	 */
	public function load_plugincompatibility() {
		$plugin_compatibility = file( oik_path( "opinions/plugincompatibility.csv", "oik-block" ) );
		//echo count( $plugin_compatibility );
		$this->plugin_compatibility = array();
		$count = 0;
		foreach ( $plugin_compatibility as $line ) {
			$csv = str_getcsv( $line );
			$plugin = bw_array_get( $csv, 0, null );
			$compatibility = bw_array_get( $csv, 1, null );
			//$count++;
			//echo "$plugin $compatibility $count" . PHP_EOL;
			//if ( $plugin && $compatibility ) {
			//if ( !isset( $this->plugin_compatibility[ $plugin ] ) ) {
				$this->plugin_compatibility[ $plugin ] = $compatibility;
				//if ( count( $this->plugin_compatibility ) != $count ) {
				//	echo "Duplicate !";
					//gob();
				//}
		}
		//echo "After" . PHP_EOL;
		//echo count( $this->plugin_compatibility );
	
	}


}
