<?php 

/**
 * @package oik-block
 * @copyright Bobbing Wide 2018
 * 
 * Converts shortcodes to blocks
 * 
 * Stage 1. Estimate the effort to convert
 * 
 * 1. List all shortcodes
 * 2. Count parameters
 * 3. Identify implementing plugins
 * 4. Guestimate conversion
 * 
 */
 


 
function oik_block_estimator() {

	do_action( "oik_add_shortcodes" );
	
	oik_require( 'shortcodes/oik-codes.php' );
  $sc_list = bw_shortcode_list();
	echo "Shortcode,Desc,plugin,#parameters" . PHP_EOL;
	$total_parms = 0;
	foreach ( $sc_list as $shortcode => $desc ) {
		$sc_syntax = _bw_lazy_sc_syntax( $shortcode );
		$parms = count( $sc_syntax );
		$plugin = oik_block_query_plugin( $shortcode );
		echo "$shortcode,$desc,$parms,$plugin" . PHP_EOL;
		//echo count( $sc_syntax ) ;
		$total_parms += $parms;
		
		
	
	}
	echo count( $sc_list ) . ",,#plugins,$total_parms " . PHP_EOL;
	

}

function etcetar() {
 global $bw_sc_ev, $bw_sc_ev_pp, $bw_sc_te;
 
 // Event implementing function
 
 var_dump( $bw_sc_ev );
 
 // Event post processing
 
 //var_dump( $bw_sc_ev_pp );
 
 // 
 
 // Title expansion ? 
 // If it can't be expanded in a title then it's probably a block shortcode
 // 
 //var_dump( $bw_sc_te );
}

function oik_block_scfile( $code=null ) {
	global $bw_sc_file;
	$file = bw_array_get( $bw_sc_file, $code, null );
	//echo $file;
	return $file;
	 
}

function oik_block_anonymous( $file ) {
	oik_require( "includes/bwtrace.php", "oik-bwtrace" );
	//$fil = bw_trace_anonymize_symlinked_file( $file );
	
	global $bw_trace_anonymous;
	$bw_trace_anonymous = true;
	
	$fil = bw_trace_file_part( $file );
	//echo PHP_EOL;
	//echo $fil;
	return $fil;
}

function oik_block_plugin( $fil ) {
	$parts = explode( "/",  $fil );
	//print_r( $parts );
	$plugin = $parts[2];
	return $plugin;
}

function oik_block_query_plugin( $code ) {
	$file = oik_block_scfile( $code );
	if ( $file ) {	
		$plugin = oik_block_plugin( oik_block_anonymous( $file ) );
	} else {
		$plugin = "core";
	}
	
	return $plugin;
}

function oik_block_count_plugins() {
	global $plugins;
	

}

	 

oik_block_estimator();

/**
$plugin = oik_block_plugin( oik_block_anonymous( oik_block_scfile( "oik" ) ) );

echo PHP_EOL;
echo $plugin;
echo PHP_EOL;

$plugin = oik_block_plugin( oik_block_anonymous( oik_block_scfile( "gallery" ) ) );
echo $plugin;

echo PHP_EOL;

//etcetar();

*/


