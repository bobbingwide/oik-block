<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class oik_block_editor_opinion {
 
 
/**
 * Gathers expert opinions to determine the preferred editor.
 * 
 * Each opinion is an oik_block_editor_opinion object
 * 
 *	of the form:
 * 
 * string $preferred_editor values A=Any / Ambivalent, B=Block, C=Classic, other=tbc
 * bool $mandatory true if there's no choice in the matter
 * string $level At which the opinion was set
 * string $observation
 * string $choice_of_action
 * 
 * Opinions are gathered using the 'oik_block_gather_opinions' filter passing $opinions, $post
 *
 */
 
 
	/**
	 * Preferred editor can be one of three values.
	 * 
	 * It can't yet be extended. 
	 *
	 * Value | Meaning
	 * ----- | -------- 
	 * A     | Any / Ambivalent - means either
	 * B     | Block 
	 * C     | Classic
	 */
	public $preferred_editor;
	
	/**
	 * When an opinion is mandatory then this is what should be used as the editor
	 * class oik_block_editor_opinions is responsible for dealing with differences
	 */
	public $mandatory;
	
	/**
	 * An opinion can be set at multiple levels
	 * 
	 * Level | Meaning
	 * ----- | --------
	 * S     | Site 
	 * T     | Post type 
	 * P     | Post content
	 * U     | User
	 * 
	 * The User's opinion will normally override other opinions except Mandatory ones
   */
	public $level;
	
	/**
	 * Translatable explanation of the opinion
	 */
	public $observation;
	
	
	/**
	 * Advice on what to do
	 */
	public $choice_of_action;
	 
	
	public function __construct( $preferred_editor='A', $mandatory=false, $level="P", $observation="", $choice_of_action="" ) {
		$this->set_preferred_editor( $preferred_editor );
		$this->mandatory = $mandatory;
		$this->set_level( $level );
		$this->observation = $observation;
		$this->choice_of_action = $choice_of_action;
	
	}
	
	public function set_preferred_editor( $preferred_editor='A' ) {
		$preferred_editor = strtoupper( substr( $preferred_editor . "A", 0, 1 ) );
		switch ( $preferred_editor ) {
			case 'A':
			case 'B':
			case 'C':
				$this->preferred_editor = $preferred_editor;
			  break;
				
			default:
				// Issue Error?  
				$this->preferred_editor = null;
				gob();
		}
		return $this->preferred_editor;
	}
	
	public function set_level( $level ) {
		$this->level = $level;
		return $this->level;
	}
	
	public function get_preferred_editor() {
		return $this->preferred_editor;
	}
	
	public function get_mandatory() {
		if ( $this->mandatory ) {
			$mandatory = "M";
		} else {
			$mandatory = "O";
		}
		return $mandatory;
	}
	
	public function report() {
		$row = array();
		$row[] = $this->get_preferred_editor();
		$row[] = $this->get_mandatory();
		$row[] = $this->level;
		$row[] = $this->observation;
		$row[] = $this->choice_of_action;
		$this->tablerow( $row );
	}
	
	public function tablerow( $row ) {
		if ( "cli" == php_sapi_name() ) {
			echo implode( " | ", $row ) . PHP_EOL;
		} else {
			bw_tablerow( $row );
		} 
	}



}
