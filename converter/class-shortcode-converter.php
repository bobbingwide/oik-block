<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class Shortcode_converter {

	public $mixed_shortcodes = null;
	public $inline_shortcodes = null;  																				
	
	public function __construct()  {
	}
	
	/**
	 * Sets the array of mixed shortcodes
	 *
	 * Mixed shortcodes may be inline or block, depending on parameters
	 * e.g. [bw_plug oik-bob-bing-wide] is inline - it creates a link to the oik-bob-bing-wide plugin
	 * [bw_plug name="oik,oik-bob-bing-wide] is block - it creates a table 
	 */ 
	public function set_mixed_shortcodes() {
		$shortcodes = array();
		$shortcodes['oik'] = "bw_admin bw_loginout";
		$shortcodes['oik-bob-bing-wide'] = "bw_action bw_dash bw_plug";
		$shortcodes['oik-fields'] = "bw_field";
		$shortcodes['oik-shortcodes'] = "codes md";
		$shortcode_list = implode( " ", $shortcodes );
		$this->mixed_shortcodes = explode( " ", $shortcode_list ); 
		bw_trace2( $this->mixed_shortcodes, "mixed shortcodes" );
		
	}
	
	/**
	 * Sets the array of inline shortcodes
	 */
	public function set_inline_shortcodes() {
		$codes = "bw bw_abbr bw_acronym bw_contact bw_contact_button ";
		$codes .= "bw_count bw_email bw_facebook bw_flickr bw_google bw_google_plus bw_google-plus bw_googleplus ";
		$codes .= "bw_instagram bw_link bw_linkedin bw_mailto bw_mob bw_parent bw_picasa bw_pinterest ";
		$codes .= "bw_tel bw_twitter bw_youtube lbw loik OIK oik";
		$shortcodes = array();
		
		$shortcodes['oik'] = $codes;
		$shortcodes['oik-bob-bing-wide'] = "artisteer bp bw_option bw_page github lartisteer lbp ldrupal lwp lwpms wp wpms"; 
		$shortcodes['oik-shortcodes'] = "api";
		$shortcode_list = implode( " ", $shortcodes );
		$this->inline_shortcodes = explode( " ", $shortcode_list );
		bw_trace2( $this->inline_shortcodes, "inline shortcodes" );
	}
	
	public function query_inline_shortcodes( $inline_shortcodes ) {
		$this->set_inline_shortcodes();
		foreach ( $this->inline_shortcodes as $shortcode ) { 
			//if ( $shortcode ) {
				$inline_shortcodes[ $shortcode ] = $shortcode;
			//}
		}
		return $inline_shortcodes;
	}
}
