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

	public $opinions;				 
	public $pre_requisites_satisfied = false;
	public $hooks; 
	 
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
			$this->get_hook_list();
			$this->process_hook_list();
		}
			
	}
	
	function check_prerequisites() {
		if ( !function_exists( "bw_trace_get_attached_hooks" ) ) {
			oik_require( "includes/bwtrace-actions.php", "oik-bwtrace" );
		}
		if ( !function_exists( "bw_trace_get_attached_hooks" ) ) {
			$this->add_opinion( "A", "O", "Cannot perform hook analysis", "Install oik-bwtrace" );
			$this->pre_requisites_satisfied = false;
			
		}	else {
			$this->pre_requisites_satisfied = true;
		}
		return $this->pre_requisites_satisfied;


	}
	
	function add_opinion( $edit, $mandatory, $observation, $notes=null ) {
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
	
	function process_hook_list() {
		foreach ( $this->hooks as $hook ) {
			//echo $hook . PHP_EOL;
			$this->get_attached_hooks( $hook );
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
		echo $hook;
		echo "!";
		echo $attached;
		echo "!";
		echo PHP_EOL;
		return $attached;
	}
	
	function get_opinions() {
		if ( 0 === count( $this->opinions ) ) {
			$this->opinions[] = new oik_block_editor_opinion( "A", false, "S", "No problems with hooks detected", "TBC" );
		}
		return $this->opinions;
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
