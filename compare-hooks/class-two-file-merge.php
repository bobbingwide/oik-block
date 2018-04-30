<?php 
 
/** 
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 * 
 * Merges file1 and file2 into a new file	outfile
 *
 */
class two_file_merge extends two_file_scan {

	function __construct() {
		parent::__construct();
	
	}
	
	/**
	 * Merge the files
	 * 
	 * Similar logic to two file scan 
	 * but writes a merged [hook] shortcode
	 * when it gets a match.
	 *
	 */
	
	function merge() {
		$this->oline( "Merging two files" );
		
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
				$this->merge_match( $this->gparts, $this->cparts );
				$gi = $this->nexti( $gi, $this->gc );
				$ci = $this->nexti( $ci, $this->cc );
			} elseif ( $this->ghook > $this->chook ) {
			 
				$this->write( $this->cline );
				$ci = $this->nexti( $ci, $this->cc );
			   
			} else { /* ghook < chook - new Gutenberg hook */
				$this->write( $this->gline );
				$gi = $this->nexti( $gi, $this->gc );
			}
			//echo PHP_EOL;
		}
	
	}
	
	function merge_match( $gparts, $cparts ) {
		$line = array();
		$line[] = "[hook";
		$line[] = $this->get_hook( $gparts );
		$line[]	= $this->get_type( $gparts );
		$line[] = max( $this->get_num_args( $gparts ), $this->get_num_args( $cparts ) );
		$line[] = $this->get_count( $gparts ) + $this->get_count( $cparts );
		$line[] = max( $this->get_attached( $gparts ), $this->get_attached( $cparts ) );
		$merged = implode( " ", $line ) . ']' . PHP_EOL;
		$this->write( $merged );
	
	}
	
	function report() {
		$this->oline( "Merged report" );
	}

}
