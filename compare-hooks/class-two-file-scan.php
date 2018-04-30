<?php 
/** 
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 */
class two_file_scan {

	public $added = 0;
	public $changed = 0;
	public $deleted = 0;
	public $same = 0;
	

	function __construct() {  

		//$this->g813names = file( "../compare-hooks/g813.names" );
		//$this->c813names = file( "../compare-hooks/c813.names" );
		$this->g813names = array();
		$this->c813names = array();
		$this->output_file = null;
	}
	
	function file1( $file ) {
		$this->g813names = file( $file, FILE_SKIP_EMPTY_LINES || FILE_IGNORE_NEW_LINES );
		//echo count( $this->g813names );
		//print_r( $this->g813names );
	}
	
	function file2( $file ) {
		$this->c813names = file( $file, FILE_SKIP_EMPTY_LINES || FILE_IGNORE_NEW_LINES );
	}
	
	/**
	 * Sets the output file 
	 */
	function output_file( $output_file ) {
		$this->output_file = $output_file;
		if ( file_exists( $output_file ) ) {
			unlink( $output_file );
		}
	
	}
	
	
	function summary() {
	  $this->oline( "Total Added" , $this->added );
		$this->oline( "Total Changed" , $this->changed );
		$this->oline( "Total Deleted" , $this->deleted );
		$this->oline( "Total Same" , $this->same );
	}
	
	function oline( $text, $value=null ) {
		echo " " . $text . ',' . $value . PHP_EOL;
	}
	
	function init() {
	
		$this->gc = count( $this->g813names );
		$this->cc = count( $this->c813names );
	
		echo "G " . $this->gc . PHP_EOL;
		echo "C " . $this->cc . PHP_EOL;
		
		$this->added = 0;
		$this->changed = 0;
		$this->deleted = 0;
		$this->same = 0;
	}
	
	
	
	function scan() {
		$this->init();
		$gi = 0;
		$this->gline = $this->g813names[ $gi ]; 
	
		//echo $gline;
	
		$ci = 0;
		$this->cline = $this->c813names[ $ci ];
		//echo $cline;
	
		
		while ( ( ( $ci  ) < $this->cc ) && ( ( $gi  ) < $this->gc ) ) {
			if ( $gi < $this->gc ) {
				$this->gline = $this->g813names[ $gi ];
				$this->gparts = $this->get_parts( $this->gline );
				$this->ghook = $this->get_hook( $this->gparts );
			}
			if ( $ci < $this->cc ) { 
				$this->cline = $this->c813names[ $ci ];
				$this->cparts = $this->get_parts( $this->cline );
				$this->chook = $this->get_hook( $this->cparts );
			}
			if ( $this->ghook == $this->chook ) {
				$this->compare_matches( $this->gparts, $this->cparts );
				$gi = $this->nexti( $gi, $this->gc );
				$ci = $this->nexti( $ci, $this->cc );
			} elseif ( $this->ghook > $this->chook ) {
			 
				$this->deleted_classic( $this->cparts );
				$ci = $this->nexti( $ci, $this->cc );
			   
			} else { /* ghook < chook - new Gutenberg hook */
				$this->new_hook( $this->gparts );
				$gi = $this->nexti( $gi, $this->gc );
			}
			//echo PHP_EOL;
		}
	
	}
	
	/**
	 * Sets the next index
	 
	 * a[0]		1
	 * a[1]		2
	 * a[2] 	3
	 * 
	 * Increments the index until the end is reached.
	 * @param integer $index the array index
	 * @param integer $count the number of items in the array
	 * @return integer next index
	 */ 
	function nexti( $index, $count ) {
		//if ( ( $index + 1 ) < $count ) {
			$index++;
		//}
		//echo $index;
		return $index;
	}
	/**
	 * Converts line into an array of parts for each "hook" shortcode
	
	 * `[hook $hook $type $num_args $count $attached]`
	 */
	
	function get_parts( $line ) {
		
		$line = trim( $line );
		$line = str_replace( "[hook ", "", $line );
		$line = str_replace( "]", "", $line );
		$parts = explode( " ", $line );
		if ( count( $parts ) != 5 ) {
			echo $line;
			print_r( $parts );
			gob();
		}
		return $parts;
	}

	function get_hook( $parts ) {
		return $parts[ 0 ];
	}

	function get_type( $parts ) {
		return $parts[ 1 ];
	}
	
	function get_num_args( $parts ) {
		return $parts[ 2 ];
	}
	
	function get_count( $parts ) {
		return $parts[ 3 ];
	}
	
	
	function get_attached( $parts ) {
		return $parts[ 4 ];
	}
		

	function compare_matches( $gparts, $cparts ) {
		if ( $this->get_hook( $gparts ) != $this->get_hook( $cparts ) ) {
			$this->oline( "Hook name different. Logic error", $this->ghook );
			die();
		}
		if ( $this->get_type( $gparts ) != $this->get_type( $cparts ) ) {
			$this->oline( "Hook type changed!", $this->ghook );
			
			die();
		}
		
		if ( $this->get_num_args( $gparts ) != $this->get_num_args( $cparts ) ) {
			$this->oline( "Num args changed", $this->ghook );
			die();
		} 
		
		if ( $this->get_attached( $gparts ) != $this->get_attached( $cparts ) ) {
			$this->oline( "Attached hooks changed", $this->ghook );
			$this->report_change( "Attached hooks changed", $gparts, $cparts );
			$this->changed++;
		} elseif ( $this->get_count( $gparts ) != $this->get_count( $gparts ) ) {
			$this->oline( "Invocations changed", $this->ghook );
			$this->report_change( "Invocations changed", $gparts, $cparts );
			$this->changed++;			
		} else {
		
		
      //echo "Match," . $this->ghook;
			$this->same++;
		}
	}

	function deleted_classic( $cparts ) {
	
		$this->oline( "Deleted" , $this->chook );
		$this->report_change( "Deleted", null, $cparts );
		$this->deleted++;			

	}

	function new_hook( $gparts ) {
 		$this->oline( "Added" , $this->ghook );
		$this->report_change( "Added", $gparts, null );
		$this->added++;
	}
	
	/**
	 * Hook             | Type   | Change         | Details                                                           |	Observation 
	 * ---------------- |------- | -------------  | ----------------------------------------------------------------  | -------
	 * admin_body_class | filter | attached count | Gutenberg adds "gutenberg-editor-page" (inside "replace_editor" ) | None
	 * admin_enqueue_scripts | action | attached count | 
	 */
	
	
	function report_change( $change, $gparts, $cparts ) {
		if ( $gparts ) {
			$parts = $gparts;
		} else {
			$parts = $cparts;
			$gparts = array( null, null, null, null, null );
		}
		
		if ( !$cparts ) {
			$cparts = array( null, null, null, null, null );	
		}
		$line = array();
		$line[] = $this->get_hook( $parts );
		$line[] = $this->get_type( $parts );
		$line[] = $change;
		$line[] = $this->gvc( $this->get_num_args( $gparts ) , $this->get_num_args( $cparts ) );
		$line[] = $this->gvc( $this->get_attached( $gparts ) , $this->get_attached( $cparts ) );
		$line[] = $this->gvc( $this->get_count( $gparts ) , $this->get_count( $cparts ) );
		
		$this->write( implode( "|", $line ) );
		//echo PHP_EOL;
	}
	
	function report_header() {
		$line = array();
		$line[] = "Hook";
		$line[] = "Type";
		$line[] = "Change";
		$line[] = "Num args";
		$line[] = "Attached";
		$line[] = "Count";
		
		
		$this->write( implode( "|", $line ) );
		//echo PHP_EOL;
		
	}
	
	function gvc( $gpart, $cpart ) {
		$part = $gpart;
		if ( $gpart !== $cpart ) {
			$part .= " ";
			$part .= $cpart;
		}
		return $part;
	}
	
	
	function write( $line ) {
		static $unwritten = array();
		if ( !$this->output_file ) {
			echo $line . PHP_EOL;
			return;
		}
		$handle = fopen( $this->output_file, "a" );
		if ( $handle === FALSE ) {
			//bw_trace_off();
			// It would be nice to let them know... 
			$ret = "fopen failed";
			if ( isset( $unwritten[ $this->output_file ] ) ) {
				$unwritten[ $this->output_file ] .= $line;
			} else {  
				$unwritten[$this->output_file] = $line;
			}
		} else {
			if ( isset( $unwritten[ $this->output_file ] ) ) {
				$bytes = fwrite( $handle, "bw_write unwritten" );
				$bytes = fwrite( $handle, $unwritten[ $this->output_file ] );
				unset( $unwritten[ $this->output_file ] );
			}
			$bytes = fwrite( $handle, $line );
			$ret = fclose( $handle );
			$ret .= " $bytes {$this->output_file} $line";
		}
		return( $ret );
} 
	
	
}
