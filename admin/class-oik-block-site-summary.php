<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */
 
 
class oik_block_site_summary {

	function __construct() {
	}
	
	function report() {
		$this->report_content();
		//$this->report_analysis();
	}
	
	function report_content() {
		oik_require( "shortcodes/oik-content.php", "oik-block" );
		
		oik_require( "libs/class-BW-table.php", "oik-block" );
		BW_table::set_format( 'md' );
		echo oik_block_content();
	}
	
	/**
	 * Reports the percentage compatible of the analysed posts
	 */
	
	function report_analysis() {
		$counts = $this->select_counts();
		$total = array_sum( $counts );
		$compatible = $this->compatible( $counts );
		$percentage = $this->percentage( $compatible, $total );
		printf( 'Compatible: %1$s', $compatible );
		echo PHP_EOL;
		
		printf( 'Percentage compatible: %1$s', $percentage ); 
		echo PHP_EOL;
		printf( 'Analysed: %1$s ', $total );
		echo PHP_EOL;
	}
	
	function percentage( $value, $total ) {
		if ( $total ) {
			$value *= 100;
			$percentage = $value / $total;
			$percentage = number_format( $percentage, 1 ) . __( '%', 'oik_block' );
		}	else {
			$percentage = 'na';
		}
		return $percentage;
	}
		
	function compatible( $counts ) {
		//print_r( $counts );
	
		$compatible = bw_array_get( $counts, "AM", 0 );
		$compatible += bw_array_get( $counts, "AO", 0 );
		$compatible += bw_array_get( $counts, "BM", 0 );
		$compatible += bw_array_get( $counts, "BO", 0 );
		return $compatible;
	}
	
	function select_counts() {
		global $wpdb;
		$request =  "select count(*) count, meta_value";
		$request .= " from {$wpdb->postmeta}";
		$request .= " where meta_key = '_oik_block_editor'";
		$request .= " group by meta_value";
		$request .= " order by meta_value";
		$results = $wpdb->get_results( $request );
		//bw_trace2( $results );
		$counts = array();
		foreach ( $results as $result ) {
			$counts[ $result->meta_value ] = $result->count;
		}
		bw_trace2( $counts );
		return $counts;
	}
	
	/**
	 * Produces the overall gut feel for the site
	 * 
	 */
	function gut_feel() {
		$this->report_analysis();
		$this->report_potential();
	}
	
	/**
	 * Reports the Potential
	 *   
	 * Potential is the number of posts that could be edited using the Block editor
	 * Currently stored in global arrays populated by oik_block_content().
	 */
	function report_potential() {
		global $bw_editable_plugins, $bw_editable_counts;
		$potential = bw_array_get( $bw_editable_counts, 'Block', 0 );
		echo "Potential: " . $potential . PHP_EOL;
		$total = array_sum( $bw_editable_counts );
		$revisions = bw_array_get( $bw_editable_counts, 'Revisions', 0 );
		$site = $total - $revisions;
		echo "Site: " . $site . PHP_EOL;
		$percentage = $this->percentage( $potential, $site );
		echo "Block editor coverage: " . $percentage . PHP_EOL;
	}
	/**
	 * Resets all decisions for the current site 
	 */
	function reset_decisions() {
		global $wpdb;
		echo "Deleting all Editor decisions" . PHP_EOL;
		$where = array( "meta_key" => "_oik_block_editor" );
		$deleted = $wpdb->delete( $wpdb->postmeta, $where );
		echo "Deleted: $deleted" . PHP_EOL;
	}
	



} 
 
 
