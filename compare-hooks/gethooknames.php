<?php 

/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package oik-block
 * 
 * Syntax: php gethooknames.php tracefile > file.names
 *
 * Extracts the [hook] shortcodes from the trace output	where the hook_links are sorted by hook name
 * 
 * Would be nice to know what the request is and group the output as Gutenberg / Classic 
 * to make the multi file merge and two file scan easier.
 */
 
if ( $argc > 1 ) {
	$filename = $argv[ 1 ];
} else {
	echo "Syntax: php gethooknames.php tracfile > files.names" . PHP_EOL;
	die();
}

if ( !file_exists( $filename ) ) {
	echo "File $filename does not exist" . PHP_EOL;
	die();
} 


$lines = file( $filename );
//echo count( $lines );
gethooks( $lines );


/**


wp-content/plugins/oik-bwtrace/includes/oik-action-counts.php(205:0) 
bw_trace_create_hook_links(4) 393 2018-04-26T11:47:17+00:00 7.938495 0.009819 cf=shutdown 15 0 6291456/6291456 512M F=382 hook_links <h3>by hook name</h3>
 *
 * ` 
 * ...
 
 * ... hook_links <h3>by hook name</h3>
 * [
 * [
 * ... cf=shutdown 15 0 6291456/6291456 512M F=382 count hooks 482
 * ...
 * `
 * 
 */
function gethooks( $lines ) {

	$found_hook = false;
	$count = 0;
	$hooks = 0;
	foreach ( $lines as $line ) {
		$count++;
		//echo $count;
		if ( $found_hook === false ) {
      $found_hook = strpos( $line, "hook_links <h3>by hook name</h3>" );
			if ( $found_hook !== false ) {
				//echo "line";
				//gob();
			continue;
			}
		}
		if ( $found_hook ) {
			if ( substr( $line, 0, 6 ) === "[hook " ) {
				$hooks++;
				echo $line;
			} else {
				break;
			}
		}
	}
	//echo "Found $hooks hooks" . PHP_EOL;


}
	

 
