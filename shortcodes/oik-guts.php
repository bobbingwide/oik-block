<?php // (C) Copyright Bobbing Wide 2018

/**
 * Implement [guts] shortcode
 * 
 * Displays information about WordPress and/or Gutenberg
 * 
 */

function oik_block_guts( $atts, $content, $tag ) {

//	p( bw_wp( array( "v" ) ) ); // [wp v]
global $wp_version;
p( "WordPress version: " . $wp_version ); 
  // gutenberg version 
	p( "Gutenberg version: "  . oik_block_gutenberg_version() );
	
	//oik_block_display_constant( "GUTENBERG_VERSION", "string" );
	oik_block_display_constant( "GUTENBERG_LOAD_VENDOR_SCRIPTS", "bool" );
	oik_block_display_constant( "GUTENBERG_LIST_VENDOR_ASSETS", "bool" );
	oik_block_display_constant( "GUTENBERG_DEVELOPMENT_MODE", "bool" );


 return bw_ret();

}

/**
 * Returns the Gutenberg version, if it's activated
 * 
 * @return string value of GUTENBERG_VERSION constant
 */
function oik_block_gutenberg_version() {
	$version = null;
	if ( defined( 'GUTENBERG_VERSION' ) ) {
		$version = GUTENBERG_VERSION;
	}
	return $version;
}  
	



/**
 * Display the value of a constant
 *
 * But only if it's defined
 */
function oik_block_display_constant( $field, $type, $extra=null ) {
	$displayed = defined( $field );
	if ( $displayed ) {
		$value = constant( $field );
		p( "$field $value $type $extra" );
		//$this->tablerow( $field, $value, $type, $extra );
	} else {
		//p( "$field undefined $type $extra" );
	}
	return( $displayed );
}
