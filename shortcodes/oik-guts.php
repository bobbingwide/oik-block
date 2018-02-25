<?php // (C) Copyright Bobbing Wide 2018

function oik_block_guts( $atts, $content, $tag ) {
 oik_block_display_constant( "GUTENBERG_LOAD_VENDOR_SCRIPTS", "bool" );
 oik_block_display_constant( "GUTENBERG_LIST_VENDOR_ASSETS", "bool" );
 oik_block_display_constant( "GUTENBERG_DEVELOPMENT_MODE", "bool" );


 return bw_ret();

}



/**
 * Display the value of a constant
 */
function oik_block_display_constant( $field, $type, $extra=null ) {
	$displayed = defined( $field );
	if ( $displayed ) {
		$value = constant( $field );
		p( "$field $value $type $extra" );
		//$this->tablerow( $field, $value, $type, $extra );
	} else {
		p( "$field undefined $type $extra" ); 
	}
	return( $displayed );
}
