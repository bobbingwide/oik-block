<?php 

/** 
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 * 
 * Merges two hook files
 *
 * Syntax: php mergehooks.php file-one file-two [output-file]
 *	
 * When performing a two file merge
	
	 	Creates a file combining the values in file-one and file-two, keyed by the hook name.
		
		
		For non key fields:
		- type = is expected to match - uses the value from file1
		- num_args = is expected to match - uses the larger
		- count is accumulated
		- attached = uses the larger
	 
	 
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
	$file1 = $argv[ 1 ];
	$file2 = $argv[ 2 ];
	$output_file = get_output( $argc, $argv, 3 );
} else {
	echo "Syntax: php mergehooks.php file-one file-two [output-file]";
	echo PHP_EOL;
	die();
}

require_once( __DIR__ . "/class-two-file-scan.php" );
require_once( __DIR__ . "/class-two-file-merge.php" );

$merger = new two_file_merge();
$merger->file1( $file1 );
$merger->file2( $file2 );
$merger->output_file( $output_file );
$merger->merge();
$merger->report();


function get_output( $argc, $argv, $index ) {
	$value = null;
	if ( $argc > $index ) {
		$value = $argv[ $index ];
	}
	return $value;
}



