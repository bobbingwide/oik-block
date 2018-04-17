<?php 
/* Two file scan
 
   Compares g813.names vs c813.names summarising changes
	 Where c813.names is the master ( Classic editor ) and we're looking for changes in the hooks that are invoked.
	 
	 g813.tree and c813.tree is the 
	 
	 
The logic in oik_block_hook_checker only works for those hooks which are registered when the analysis is performed.
If a plugin / theme attached hooks dynamically ( which happens a lot ) then
the summary of attached hooks may not include the hook functions attached by that plugin.
	 
	 
c813.names extract
		
[hook _admin_menu action 0 1 1]
[hook add_admin_bar_menus action 0 1 0]
[hook add_menu_classes filter 1 1 0]
[hook add_meta_boxes action 2 1 2]
[hook add_meta_boxes_post action 1 1 0]
[hook admin_action_edit action 0 1 0]
[hook admin_bar_init action 0 1 0]
[hook admin_bar_menu action 1 1 16]

[hook admin_body_class filter 1 1 1]
[hook admin_enqueue_scripts action 1 1 11]

	
g813.names extract
 
[hook _admin_menu action 0 1 1]
[hook add_admin_bar_menus action 0 1 0]
[hook add_menu_classes filter 1 1 0]
[hook add_meta_boxes action 2 1 2]
[hook add_meta_boxes_post action 1 1 0]
[hook admin_action_edit action 0 1 0]
[hook admin_bar_init action 0 1 0]
[hook admin_bar_menu action 1 1 16]

[hook admin_body_class filter 1 1 0]
[hook admin_enqueue_scripts action 1 1 8]
*/

$scanner = new two_file_scan();
$scanner->scan();
$scanner->summary();

class two_file_scan {

	public $added = 0;
	public $changed = 0;
	public $deleted = 0;
	public $same = 0;

	function __construct() {  

		$this->g813names = file( "../play/g813.names" );
		$this->c813names = file( "../play/c813.names" );
		$this->gc = count( $this->g813names );
		$this->cc = count( $this->c813names );
	
		echo "G " . $this->gc . PHP_EOL;
		echo "C " . $this->cc . PHP_EOL;
		
		$this->added = 0;
		$this->changed = 0;
		$this->deleted = 0;
		$this->same = 0;
	}
	
	function summary() {
	  $this->oline( "Total Added" , $this->added );
		$this->oline( "Total Changed" , $this->changed );
		$this->oline( "Total Deleted" , $this->deleted );
		$this->oline( "Total Same" , $this->same );
	}
	
	function oline( $text, $value ) {
		echo $text . ',' . $value . PHP_EOL;
	}
	
	
	function scan() {
		$gi = 0;
		$this->gline = $this->g813names[ $gi ]; 
	
		//echo $gline;
	
		$ci = 0;
		$this->cline = $this->c813names[ $ci ];
		//echo $cline;
	
		
		while ( ( ( $ci + 1 ) < $this->cc ) || ( ( $gi + 1 ) < $this->gc ) ) {
			$this->gline = $this->g813names[ $gi ];
			$this->cline = $this->c813names[ $ci ];
			$this->gparts = $this->get_parts( $this->gline ); 
			$this->ghook = $this->get_hook( $this->gparts );
			$this->cparts = $this->get_parts( $this->cline );
			$this->chook = $this->get_hook( $this->cparts );
		
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
			echo PHP_EOL;
		}
	
	}
	
	/**
	 * Sets the next index
	 * 
	 * Increments the index until the end is reached.
	 * @param integer $index the array index
	 * @param integer $count the number of items in the array
	 * @return integer next index
	 */ 
	function nexti( $index, $count ) {
		if ( ( $index + 1 ) < $count ) {
			$index++;
		}
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
			$this->changed++;
		} elseif ( $this->get_count( $gparts ) != $this->get_count( $gparts ) ) {
			$this->oline( "Invocations changed", $this->ghook );
			$this->changed++;			
		} else {
		
		
      //echo "Match," . $this->ghook;
			$this->same++;
		}
	}

	function deleted_classic( $cparts ) {
	
			echo "Deleted," . $this->chook;
		$this->deleted++;			

	}

	function new_hook( $gparts ) {
 		echo "Added," . $this->ghook;
		$this->added++;
	}
	
	/**
	 * Hook             | Type   | Change         | Details                                                           |	Observation 
	 * ---------------- |------- | -------------  | ----------------------------------------------------------------  | -------
	 * admin_body_class | filter | attached count | Gutenberg adds "gutenberg-editor-page" (inside "replace_editor" ) | None
	 * admin_enqueue_scripts | action | attached count | 
	 */
	
	
	function report_change( $gparts, $cparts ) {
	
	
}
