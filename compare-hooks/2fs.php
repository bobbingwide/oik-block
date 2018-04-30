<?php 
/** 
	@copyright (C) Copyright Bobbing Wide 2018
	@package oik-block
	
	
	Two file scan
	
	Syntax: php 2fs.php gfile cfile [output-file]
	
	When performing a two file scan
 
   Compares gfile ( g813.names ) vs cfile ( c813.names ) summarising changes, which are optionally written to the output-file
	 or to stdout if not.
	 
	 Where: 
	 gfile is the merged new file ( Gutenberg editor )
	 cfile is the master file ( Classic editor ) and we're looking for changes in the hooks that are invoked.
	 
	 
	 
	 g813.tree and c813.tree are the files we could use to determine if the sequence in which the hooks are run has changed.
	 
	 
	 Notes: 
	 - The logic in oik_block_hook_checker only works for those hooks which are registered when the analysis is performed.
	 - If a plugin / theme attached hooks dynamically ( which happens a lot ) then the summary of attached hooks may not include the hook functions attached by that plugin.
	 
	 
	 
c813.names extract

```
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
```


	
g813.names extract
``` 
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
```
*/

if ( $argc > 2 ) {
	$gfile = $argv[ 1 ];
	$cfile = $argv[ 2 ];
	$output = get_output( $argc, $argv, 3 );
} else {
	echo "Syntax: php 2fs.php gfile cfile [output-file]";
	echo PHP_EOL;
	die();
}

function get_output( $argc, $argv, $index ) {
	$value = null;
	if ( $argc > $index ) {
		$value = $argv[ $index ];
	}
	return $value;
}

require_once( __DIR__ . "/class-two-file-scan.php" );
$scanner = new two_file_scan();
$scanner->file1( $gfile );
$scanner->file2( $cfile );
//$scanner->output( $output );
$scanner->report_header();
$scanner->scan();
$scanner->summary();


