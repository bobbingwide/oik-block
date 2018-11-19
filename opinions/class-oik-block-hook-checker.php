<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 * 
 */
 
/**
 * Issue #25
 *
 * Related: https://github.com/WordPress/gutenberg/issues/1316
 *
 *	Another way of checking plugin compatibility is to look internally into the code.
 *	The classic editor invokes hooks and filters in a certain way
 *	The block editor changes that way. 
 *	There's an issue to document the changes.
 *	It should be possible to build routines that determine if plugins attach functions to the hooks that are changing. 
 *	If the hook is no longer being invoked OR hooks are being invoked in a different sequence to expected there could be a problem. 
 *	As a test, let's see if we can detect an issue with
 *	-  hello-dolly, 
 *	- or any plugin that responds to the 'replace_editor' hook, 
 *	- or help hooks or screen option hooks.
 *	
 *	I'll raise a separate issue for this.
 *  
 */
 
class oik_block_hook_checker {

	/**
	 * Array of opinions
	 */
	public $opinions;				 
	public $pre_requisites_satisfied = false;
	
	/**
	 */ 
	public $hooks;
	
	/**
	 * Hook_changes - pre-calculated list of hook invocation changes
	 */
	public $hook_changes; 
	 
	function __construct() {
		$this->opinions = array();
		$this->check_prerequisites();
	}
	
	/**
	 * Use the logic from oik-bwtrace to determine the attached hook functions
	 * If any hooks that are attached are different from expected then we need to form an opinion
	 * 
	 */
	function analyse() {
		if ( $this->pre_requisites_satisfied ) {
		
			$this->load_hook_changes();
			$this->process_hook_changes();
			//$this->get_hook_list();
			//$this->process_hook_list();
		}
			
	}
	
	function check_prerequisites() {
		if ( !function_exists( "bw_trace_get_attached_hooks" ) ) {
			$file = oik_path( "includes/bwtrace-actions.php", "oik-bwtrace");
			if ( file_exists( $file )) {
				oik_require( "includes/bwtrace-actions.php", "oik-bwtrace" );
			}
		}
		if ( !function_exists( "bw_trace_get_attached_hooks" ) ) {
			$this->add_opinion( "A", false, "Cannot perform hook analysis", "Install oik-bwtrace" );
			$this->pre_requisites_satisfied = false;
			
		}	else {
			$this->pre_requisites_satisfied = true;
		}
		return $this->pre_requisites_satisfied;


	}
	
	/**
	 * Adds a Site opinion to the array of opinions
	 */
	function add_opinion( $editor, $mandatory, $observation, $notes=null ) {
		$this->opinions[] = new oik_block_editor_opinion( $editor, $mandatory, "S", $observation, $notes );
	}
	
	/**
	 * Gets the list of hooks to check
	 * 
	 * We should also be able to determine the `expected` hook list and store this nicely
	 
    [hooks] => dbx_post_advanced,gutenberg_can_edit_post_type,replace_editor
    [results] => the_content:9,the_content:10,the_content:11,the_content:12,the_content:21,the_content:99,the_content,_wp_relative_upload_path
	 */
	
	function get_hook_list() {
		$hooks = array();
		$hooks[] = "admin_body_class";
		$hooks[] = "admin_enqueue_scripts"; 
		$hooks[] = "replace_editor";
		$hooks[] = "dbx_post_advanced";
		$hooks[] = 'the_content';
		
		$this->hooks = $hooks;
	}
	
	
	/**
	 * Loads the hook-change.md file into an array keyed by hook
	 
	 *
	 * `
	 * Hook | Type | Change | Num args | Attached: G C | Count
	 * ---  | ---- | ------ | -------- | -------- | ------
	 * admin_body_class | filter | Attached hooks changed | 1 | 1 0 | 1
	 * admin_enqueue_scripts | action | Attached hooks changed | 1 | 11 8 | 1
	 * admin_post_thumbnail_html | filter | Deleted |  3 |  0 |  1
	 * after_wp_tiny_mce | action | Deleted |  1 |  0 |  1
	 * allowed_block_types | filter | Added | 2  | 0  | 1 
	 * edit_form_advanced | action | Added | 1  | 3  | 1 
	 * `
	 *
	 * Notes:
	 * - Assume we don't need to cater specifically for the first two lines
	 * - This is just an example
	 * - Don't expect more than one blank either side of the '|'s
	 * - But we allow for none.
	 */
	public function load_hook_changes() {
		$this->hook_changes = array();
		$lines = file( oik_path( "compare-hooks/data/hook-change.md", "oik-block" ) );
		foreach ( $lines as $line ) {
			$line = trim( $line );
			$line = str_replace( " |", "|", $line );
			$line = str_replace( "| ", "|", $line );
			$hook_change = explode( "|" , $line );
			$hook = $hook_change[ 0 ];
			$this->hook_changes[ $hook ] = $hook_change;
		}
	}
	
	function process_hook_changes() {
		foreach ( $this->hook_changes as $hook => $hook_change ) {
			$attached = $this->get_attached_hooks( $hook );
			if ( $attached ) {
				$this->consider_attached( $hook, $hook_change, $attached );
			}
		}
	}
	
	
	/**
	 *
	 * Consider the attached hooks and see if we can tell anything about anything
	 * 
	 
	 * Type   | Change  | Opinion
	 * ------ | ------  | -------
	 * action | Added   | Should not be a problem unless the plugin implements the hook already
	 * filter | Added   | Should not be a problem unless the plugin implements the hook already
	 * action | Deleted | A problem if the plugin responds to the hook  
	 * filter | Deleted | Could be a problem if the plugin responded to the hook
	 * action | Attached hooks changed | see below
	 * filter | Attached hooks changed | see below
	 * action | Invocations changed | @TODO - not happened yet - see Added / Deleted
	 * filter | Invocations changed | @TODO - not happened yet - see Added / Deleted
	 * 
	 * Once we've looked at all the `Attached hooks changed` changes
	 * we don't need to worry about them. See compare-hooks/data/attached-hooks-changed.md
	 

action | Cattached hooks | need to know which Gutenberg function
filter | Changed attached hooks | need to know which Gutenberg function
	 * 
	 * @param string $hook the hook name
	 * @param array $hook_change how the hook has changed
	 * @param string $attached the summary of attached hooks
	 *
	 * @TODO Implement logic based on $type and $change 
	 */
	public function consider_attached( $hook, $hook_change, $attached ) {
		$type = $hook_change[ 1 ]; 
		$change = $hook_change[ 2 ];
		
		$observation = implode( ",", $hook_change );
		
		switch ( $type . $change ) {
			case "actionAdded":
			case "filterAdded":
			
				break;
				
			case "actionDeleted":
			case "filterDeleted":
				if ( $attached ) {
					$further = $this->inspect_attached_further( $hook, $attached );
					if ( $further ) { 
						$this->add_opinion( "C", false, $observation, $attached . $further . PHP_EOL );
					}
				}
			
				break;
				
			case "actionAttached hooks changed":
			case "filterAttached hooks changed":
				break;
			
			
		}
		
		//$this->add_opinion( "A", false, $observation, $attached . PHP_EOL );
	}
	
	/** 
	 * Inspects the attached hooks more deeply
	 *
	 * Compares the attached hooks with the expected set and reports differences.
	 * 
	 * @param string $hook hook name
	 * @param string $attached currently attached hooks
	 * 
	 */
	function inspect_attached_further( $hook, $attached ) {
		$observation = null;
		$attached = trim( $attached );
		$expected = $this->get_expected( $hook );
		if ( $expected != $attached ) {
			$observation = $this->analyse_differences( $expected, $attached );
		}
		return $observation;
	
	}
	
	/**
	 * Analyses differences between expected and currently attached
	 */
	function analyse_differences( $expected, $attached ) {
		bw_trace2();
		$observation = "Hook differences detected!";
		/*
		$observation .= "<br />$expected!<br />$attached!";
		$observation .= " ";
		$observation .= strlen( $expected );
		$observation .= " ";
		$observation .= strlen( $attached );
		*/
		$expected_hooks = $this->get_hook_array( $expected );
		$attached_hooks = $this->get_hook_array( $attached );
		$added = array_diff( $attached_hooks, $expected_hooks );
		if ( $added ) {
			$observation .= "<br />Added: " . implode( "<br />", $added );
			
		}
		$deleted = array_diff( $expected_hooks, $attached_hooks );
		if ( $deleted ) {
			$observation .= "<br />Deleted: " . implode( "<br />", $deleted );
		}
		
		return $observation;
	}
	
	/**
	 * Converts the attached hooks string back into an array
	 *
	 * `
   * : 2   oik_do_shortcode;1
	 * : 8   WP_Embed::run_shortcode;1 WP_Embed::autoembed;1
	 * : 9   do_blocks;1
	 * : 10   prepend_attachment;1 wp_make_content_images_responsive;1
	 * : 11   capital_P_dangit;1 do_shortcode_earlier;1
	 * : 20   convert_smilies;1
	 * : 98   wptexturize_blocks;1
	 *: 99   bw_wpautop;1
	 */
	
	function get_hook_array( $attached_hooks ) {
		//print_r( $attached_hooks );
		$attached_hooks = str_replace( ": ", ",", $attached_hooks );
		$attached_hooks = str_replace( "   ", ",", $attached_hooks ); 
		$attached_hooks = str_replace( " ", ",", $attached_hooks );
		$explosion = explode( ",", $attached_hooks );
		$priority = null;
		$hooks = array();
		foreach ( $explosion as $priority_or_hook ) {
			if ( $priority_or_hook !== ""  ) {
				if ( is_numeric( $priority_or_hook ) ) {
					$priority = $priority_or_hook;
				} else {
					$hooks[] = "$priority;$priority_or_hook";
				}
			}
		}
		//print_r( $hooks );
		return $hooks;
		 
	}
	
	
	/**
	 * @TODO - determine need for this?
	 */

	function process_hook_list() {
		foreach ( $this->hooks as $hook ) {
			//echo $hook . PHP_EOL;
			$attached = $this->get_attached_hooks( $hook );
			
			//gob();
		}
	}
	
	/**
	 * Gets the attached hooks
	 *
	 * @param string $hook
	 * @return string attached hooks 
	 */
	function get_attached_hooks( $hook ) {
		$attached = bw_trace_get_attached_hooks( $hook );
		/*
		echo $hook;
		echo "!";
		echo $attached;
		echo "!";
		echo PHP_EOL;
		*/
		return $attached;
	}
	
	function get_opinions() {
		if ( 0 === count( $this->opinions ) ) {
			$this->opinions[] = new oik_block_editor_opinion( "A", false, "S", "No problems with hooks detected", "TBC" );
		}
		return $this->opinions;
	}
	
	/**
	 * Gets the expected hooks for WordPress core and Gutenberg
	 * 
	 * Format:
	 * `
	 * : priorityn   hook_name;args hook_name2:args\n
	 * : prioritym   another_hook;args
	 * `
	 * 
	 * Notes: 
	 * - one space between colon and priority
	 * - three spaces between priority and hook name
	 * - semi-colon between hook name and number of args
	 * - space between multiple hooks with the same priority
	 * - multiple priorities should be separated by newlines ( \n )
	 * 
	 * @param string $hook
	 * @return string expected hooks attached in format produced by oik-bwtrace
	 */
	function get_expected( $hook ) {
		$expected_attached = array( "edit_form_top" => ": 10   gutenberg_remember_classic_editor_when_saving_posts;1" 
		, "edit_page_form" => ": 10   WP_Embed::maybe_run_ajax_cache;1 gutenberg_intercept_meta_box_render;1"
		, "media_buttons" => ": 0   bw_trace_attached_hooks;9 bw_trace_backtrace;9\n: 10   media_buttons;1"
	  , "replace_editor" => ": 10   gutenberg_init;2"
		);
		$expected = bw_array_get( $expected_attached, $hook, "todo" );
		return $expected;
	}
	
																					 
	function replace_editor_expected_output() {
		$expected = ": 10   gutenberg_init;2";
		return $expected;
	}
	
	/** 
	 * The [hook] shortcode produced by oik-bwtrace
	 * is formatted as 
	 * 
	 * `[hook $hook $type $num_args $count $attached]`
	 * 
	 * If, for the edit transaction ( e.g. /src/wp-admin/post.php?post=813&action=edit ) 
	 *  we compare the hooks invoked by Gutenberg vs the hooks invoked when using the Classic editor 
	 * ( e.g. /src/wp-admin/post.php?post=813&action=edit&classic-editor=1 )
	 * we'll determine the changes
	 * 
	 * 
	 * Hook             | Type   | Change         | Details                                                           |	Observation 
	 * ---------------- |------- | -------------  | ----------------------------------------------------------------  | -------
	 * admin_body_class | filter | attached count | Gutenberg adds "gutenberg-editor-page" (inside "replace_editor" ) | None
	 * admin_enqueue_scripts | action | attached count | 
	 
	 
	 */
	

}
