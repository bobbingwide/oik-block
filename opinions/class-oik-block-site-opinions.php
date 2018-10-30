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
													 , "summarise_plugin_compatibility"
													 , "replaced_hook_checker"
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
				echo "Can't form opinion using $thought";
				gob();
			}
			 
			 
			if ( $opinion ) {
				if ( is_array( $opinion ) )  {
					foreach ( $opinion as $one_opinion ) {
						$opinions[] = $one_opinion;
					}
					
				} else {
					$opinions[] = $opinion;
				}
			}
		}
		//bw_trace2();
		return $opinions;
	}
	
	/**
	 * Checks WordPress version
	 *
	 * Gutenberg | Requires
	 * --------- | -----------
	 * 2.9.2     | 4.9
	 * 4.0.0     | 4.9.8
	 */  
	public function WordPress_version() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.9.8", ">=" ) ) {
			return new oik_block_editor_opinion( 'A', false, 'S', sprintf( 'WordPress %1$s is Block editor compatible.', $wp_version ) );
		} else {
			return new oik_block_editor_opinion( 'C', true, 'S', sprintf( 'WordPress version too low: %1$s', $wp_version ), "Upgrade WordPress" );
		}
		
	}
	
	/**
	 * Checks if the block editor is available. 
	 * 
	 * The Gutenberg plugin provides the_gutenberg_project.
	 * WordPress 5.0 delivers it as...
	 * 
	 * @TODO Check the WordPress version once something has been committed to core. 
	 */
	public function gutenberg_available() {
		if ( function_exists(  "the_gutenberg_project" ) ) {
			return new oik_block_editor_opinion( 'A', false, "S", "Block editor available" );
		}
		else {
			if ( function_exists( "has_blocks" ) ) {
				return new oik_block_editor_opinion( 'B', false, 'S', "Gutenberg built into core" );
			} else {
				return new oik_block_editor_opinion( 'C', true, 'S', "Block editor not available", "Install and activate gutenberg plugin" );
			}
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
	 * Returns array of active plugins
	 * 
	 * @return array 
	 */
	private function get_active_plugins() {
		$plugins = array();
		if ( !function_exists( "bw_get_active_plugins" ) ) {
			oik_require_lib( "oik-depends" );
		}
		if ( function_exists( "bw_get_active_plugins" ) ) {
			$plugins = bw_get_active_plugins();
		}
		return $plugins;
	}
	
	/**
	 * Analyses active plugins for their known / perceived ability to peacefully coexist with Gutenberg
	 * 
	 * For each active plugin, attempts to obtain and evaluate
	 * 
	 * - "Tested up to:" from the readme.txt file
	 * - Compatible status from the plugin compatibility database
	 * - "Gutenberg compatible:" from the readme.txt file
	 * 
	 */
	public function active_plugin_support() {
		$this->load_plugincompatibility();
		$plugins = $this->get_active_plugins();
		$this->plugin_opinions = array();
		
		foreach ( $plugins as $plugin_name => $plugin_file ) {
			$readme = $this->readme( $plugin_name );
			$tut_opinion = $this->check_tested_up_to( $readme );
			$gc_readme = $this->get_value( "Gutenberg compatible:", $readme );
			//$readme_opinion = $this->check_readme_opinion( $readme );
			$gutenberg_compatible = $this->get_gutenberg_compatible( $plugin_name );
			$gc_opinion = $this->check_gutenberg_compatible( $gutenberg_compatible, $gc_readme );
			
			$this->accumulate_plugin_opinions( $plugin_name, $tut_opinion, $gc_opinion );
		}
		$summarised_opinion = $this->summarise_plugin_opinions();
		
		$opinions = $this->reportable_opinions( $summarised_opinion );
		return $opinions;
	}
	
	/**
	 * Accumulates plugin opinions.
	 * 
	 * - Create an opinion for each plugin
	 * - Accumulate the differing opinions
	 * - If there are CO / CM opinions then we'll need to create a summary opinion
	 * - Probably a Mandatory one
	 */
	function accumulate_plugin_opinions( $plugin, $tut_opinion, $gc_opinion ) {
		$observation =  sprintf( 'Plugin: %1$s GC: %2$s, %3$s, %4$s', $plugin, $this->gc, $tut_opinion, $gc_opinion );
		$opinion = new oik_block_editor_opinion( 'A', false, 'S', $observation );
		$deliberation = "AO";
		$opinion->set_opinion( $tut_opinion );
		$deliberation = $opinion->consider( $deliberation );
		$opinion->set_opinion( $gc_opinion );
		$deliberation = $opinion->consider( $deliberation );
		$opinion->set_opinion( $deliberation );
		$this->plugin_opinions[] = $opinion;
	}
	
	/**
	 * Summarises plugin opinions
	 * 
	 * Count each plugin opinion to produce a summary opinion.
	 * 
	 * @return summarise opinion
	 */
	function summarise_plugin_opinions() {
		$summary = array();
		foreach ( $this->plugin_opinions as $opinion ) {
			$decision = $opinion->get_opinion();
			if ( !isset( $summary[ $decision ] ) ) {
				$summary[ $decision ] = 0;
			}
			$summary[ $decision ]++; 
		}
		$count_no = bw_array_get( $summary, "CM", 0 );
		$count_likely_no = bw_array_get( $summary, "CO", 0 );
		$total = count( $this->plugin_opinions );
		$notes = sprintf( 'Incompatible: %1$d, likely: %2$d, total: %3$d', $count_no, $count_likely_no, $total );
		if ( $count_no > 0 ) {
			$summarised_opinion = new oik_block_editor_opinion( "C", true, "S", "Incompatible plugins", $notes );
		} elseif ( $count_likely_no > 0 ) {
			$summarised_opinion = new oik_block_editor_opinion( "C", false, "S", "Likely incompatible plugins", $notes );
		} else {
			$summarised_opinion = new oik_block_editor_opinion( "A", false, "S", "Active plugins appear OK", $notes );
		}
		return $summarised_opinion;
	}
	
	/**
	 * Return the reportable opinions for plugins
	 *
	 * For some people the summary might be good enough
	 * Others like all the nitty gritty.
	 * Here's a compromise.
	 */ 
	private function reportable_opinions( $summarised_opinion ) {
		$reportable = array();
		$reportable[] = $summarised_opinion;
		foreach ( $this->plugin_opinions as $opinion ) {
			$decision = $opinion->get_opinion();
			if ( "CM" === $decision ) {
				$reportable[] = $opinion;
			}
			if ( "CO" === $decision ) {
				$reportable[] = $opinion;
			}
		}
		return $reportable;
	}
		
	
	/**
	 * Loads the readme.txt file, if present
	 *
	 * Some plugins don't have readme.txt files
	 * - The GitHub version of Gutenberg
	 * - Must use plugins 
	 *
	 * @param string $plugin_name the plugin slug, not the plugin file name
	 * @return array empty if there's no readme.txt file
	 */ 
	private function readme( $plugin_name ) {
		$file = WP_PLUGIN_DIR . '/'. $plugin_name . '/' . 'readme.txt';
		if ( file_exists( $file ) ) { 
			$lines = file( $file );
		} else {
			$lines = array();
			bw_trace2( $file, "No readme.txt file", true, BW_TRACE_DEBUG );
		}
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
	
	
	/** 
	 *
	 * @TODO Decide if "Requires at least" is a valid thing to test
	 
	 * My initial thought was we could use the existing value. 
	 * Further reading up on the purpose of this section suggests it's not a good test right now.
	 * This is because, the value is used by core to determine if it's safe to offer a plugin update
	 * based on the current version of core. When updating to 5.0 we're more likely to want plugins to 
	 * be brought up to the latest level before updating core. 
	 * Using ths update sequence we'll know that the plugin will peacefully coexist with Gutenberg. 
	 
	 * Perhaps in the future we can make the inferences.
	 * - If Requires at least is > 4.9 then that's probably OK
	 * - If Requires at least is >= 5.0 then it implies it needs the block editor?
	 */
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
	
	private function check_readme_opinion( $gutenberg_compatible ) {
		if ( $gutenberg_compatible ) {
			$readme_opinion = $this->map_gutenberg_compatible_to_opinion( $gutenberg_compatible );
		} else {
			$readme_opinion = null;
		}
		return $readme_opinion;
	}
	
	/**
	 * Determines Gutenberg compatibility 
	 *
	 * Compares the values obtained from the plugin compatibility database and the local plugin
	 * with the local plugin's decision taking precedence
	 * 
	 * From this value we return the mapping to an opinion

	 

	*/
	public function check_gutenberg_compatible( $gutenberg_compatible, $gc_readme ) {
		if ( $gc_readme ) {
			$this->gc = $gc_readme;
		} else {
			$this->gc = $gutenberg_compatible;
		}
		if ( $this->gc ) {
			$mapping = $this->map_gutenberg_compatible_to_opinion( $this->gc );
		} else {
			$mapping = null;
		}
		return $mapping;
	}
	
	/**
	 * Get the stored value for Gutenberg compatible
	 * 
   * Plugin evaluation, performed at Site level, will attempt to obtain the `Gutenberg compatible:` status 
	 * for each active plugin and accumulate the opinions into a single (Mandatory) opinion, 
	 * which will then be used to influence the overall decision.  
	 * 
	 * The plugin compatibility database ( https://plugincompat.danielbachhuber.com/ ) can be exported in three forms: CSV, JSON and tab separated
	 * We can load up the table, from opinions/plugincompatibility.csv, 
   * convert to an array and then index it for the required setting.
	 *  
	 * If it's available we'll consider using this value.
	 */
	public function get_gutenberg_compatible( $plugin ) {
		$gutenberg_compatible = bw_array_get( $this->plugin_compatibility, $plugin, null );
		return $gutenberg_compatible;
	}
	
	/**
	 * Maps the Gutenberg compatible value to an opinion
	 *
	 * Daniel Bachhuber's compatibility database suggests multiple values for the Gutenberg compatible setting, 
	 * which we'll map to Opinions as below
	 *
	 * Gutenberg compatible | Opinion | Notes
	 * -------------------- | ------- | -------------
	 * No                   | CM  		| Could be a bit strong! Let's find out
	 * Yes                  | AO      | 
	 * Likely-no            | CO			| 
	 * Likely-yes           | AO			| 
	 * Testing              | AO			| Assume OK
	 * Unknown              | AO			| Assume OK
	 *
	 * @param string $gutenberg_compatible
	 * @return string mapping to an opinion
	 */
	public function map_gutenberg_compatible_to_opinion( $gutenberg_compatible ) {
		$gc = strtolower( $gutenberg_compatible );
		$gc = str_replace( "-", "_", $gc );
		$mapping = array( "no" => "CM" 
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
	 * 
	 * Currently using "-gt100k" suffixed file. 
	 */
	public function load_plugincompatibility() {
		$plugin_compatibility = file( oik_path( "opinions/plugincompatibility-gt100k.csv", "oik-block" ) );
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
	
	/**
	 * Summarise the plugincompatibility status 
	 *
	 * Count the number in each status
	 * @TODO When there are enough results consider making opinions.
	 * Determine the total number
	 */
	public function summarise_plugin_compatibility() {
		$counts = array();
		foreach ( $this->plugin_compatibility as $plugin => $compatibility ) {
			if ( !isset( $counts[ $compatibility ] ) ) {
				$counts[ $compatibility ] = 0;
			}
			$counts[ $compatibility ]++;
		}
		//print_r( $counts );
	}
	
	/** 
	 * Checks replaced hooks
	 * 
	 */
	public function replaced_hook_checker() {
 		oik_require( "opinions/class-oik-block-hook-checker.php", "oik-block" );
		$hook_checker = new oik_block_hook_checker();
		$hook_checker->analyse();
		$opinions = $hook_checker->get_opinions();
		return $opinions;
	}

}
