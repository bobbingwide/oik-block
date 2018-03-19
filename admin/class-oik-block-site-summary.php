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
		$this->report_analysis();
	}
	
	function report_content() {
		oik_require( "shortcodes/oik-content.php", "oik-block" );
		
		oik_require( "libs/class-BW-table.php", "oik-block" );
		BW_table::set_format( 'md' );
		echo oik_block_content();
	}
	
	function report_analysis() {
		$counts = $this->select_counts();
		$total = array_sum( $counts );
		$compatible = $this->compatible( $counts );
		$percentage = $this->percentage( $compatible, $total );
		printf( 'Percentage compatible: %1$s%%', $percentage ); 
		echo PHP_EOL;
		printf( 'Analysed: %1$s ', $total );
		echo PHP_EOL;
	}
	
	function percentage( $value, $total ) {
		if ( $total ) {
			$value *= 100;
			$percentage = $value / $total;
			$percentage = number_format( $percentage );
		}	else {
			$percentage = "na";
		}
		return $percentage;
	}
		
	function compatible( $counts ) {
	
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
		//bw_trace2( $counts );
		return $counts;
			
		
	}
	
	
	function gut_feel() {
	
	}
	
	function reset_decisions() {
		echo "Deleting all decisions" . PHP_EOL;
		$where = array( "meta_key" => "_oik_block_editor" );
		$deleted = $wpdb->delete( $wpdn->postmeta, $where );
		echo "Deleted: $deleted" . PHP_EOL;
	}
	



} 
 
 
