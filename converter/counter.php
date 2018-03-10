<?php 

/**
 * @copyright (C) BobbingWide 2018
 * @package oik-block
 *
 * Counts instances of plugins and themes in subdirectory installs
 * 
 */
 
 
 /**
  * For each subdirectory of /apache/htdocs ( DOCROOT )
	* list directories within 
	* 
	* - wp-content\mu_plugins
	* - wp-content\plugins
	* - wp-content\themes
	*
	* and count the unique instances
	* 
	* Assuming no plugins have the same name as themes
	* 
	* Attempt to ignore
	* - Sites which are deprecated
	* - ignoring plugins with "-vn.n.n" suffices
	*/
function oik_block_counter() {

	$path = "C:/apache/htdocs";
	oik_require( "converter/class-component-counter.php", "oik-block" );
	$component_counter = new component_counter();

	$files = scandir( $path );
	foreach ( $files as $file ) {
		if ( is_dir( "$path/$file" ) ) {
			$wp_content = "$path/$file/wp-content";
			if ( file_exists( $wp_content ) ) {	
			  // @TODO Add mu-plugins
				$plugins = count_plugins( $wp_content, "plugins", $component_counter );
				$themes = count_plugins( $wp_content, "themes", $component_counter );	
				$version = oik_block_wp_version( "$path/$file" );
				echo PHP_EOL;		
				echo "$version,$file,$plugins,$themes";
			
				echo PHP_EOL;
			} else {
				echo "NOTWP,$file,," . PHP_EOL;
			}
			
			
		}
	}
	$component_counter->report();
}

function oik_block_wp_version( $url ) { 
  //$vp = $_SERVER['DOCUMENT_ROOT'] . $url . "/wp-includes/version.php";
  $vp = $url . "/wp-includes/version.php";
  global $wp_version;
	if ( file_exists( $vp ) ) {
		require( $vp );
		//echo ("version is $wp_version;" );
		//gobang();
	} else {
		$wp_version = "NOVER";
	}	
  return( $wp_version );
}



function count_plugins( $wp_content, $plugins_or_themes, $component_counter ) {
	$path = "$wp_content/$plugins_or_themes";
	$counted = 0;
	
	if ( file_exists( $path ) ) {
		$files = scandir( $path );
	
		foreach ( $files as $file ) {
			if ( is_dir( "$path/$file" ) ) {
				//echo $file;
				//echo PHP_EOL;
				echo '.';
				$component_counter->add( $file, $plugins_or_themes );
				$counted++;
			}
		}
	}	
	return $counted;
		
}

oik_block_counter();
 
 
