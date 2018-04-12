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
	
	
	/**
	 * Allows us to change our opinion
	 * 
	 * @param string $opinion - expected to be two chars or null
	 */
	public function set_opinion( $opinion ) {
		$this->set_preferred_editor( $opinion );
		$this->set_mandatory_from_opinion( $opinion );
	}
	
	public function get_opinion() {
		$next_opinion = $this->get_preferred_editor();
		$next_opinion .= $this->get_mandatory();
		return $next_opinion;
	}
	
	/**
	 * Allows us to change the importance of our opinion
	 * 
	 * @param string $opinion - expected to be two chars or null
	 */
	public function set_mandatory_from_opinion( $opinion ) {
		$mandatory = strtoupper( substr( $opinion . "OO", 1, 1 ) );
		if ( "M" === $mandatory ) {
			$this->mandatory = true;
		} else {
			$this->mandatory = false;
		}
	}
	
	/**
	 * Determines a new decision
	 * 
	 * Considers this (next) opinion in light of the current decision
	 * 
	 * This table contains some examples showing how it works. 
	 * 
	 * Current | Next opinion | New current | Notes
	 * ------- | ------------ | ----------- | -------
	 * AO      | AO           | AO					| No change so we'll return the current decision 
	 * BM      | CM           | CM          | Oops! We can't use both the Block editor and the Classic editor. Classic trumps
	 * CO      | CM           | CM          | Next opinion is Mandatory - so overrides current
	 * CM      | AM           | CM          | Oops! Ambivalent Mandatory doesn't really make sense.
	 *
	 * -There are 36 possible combinations.
	 * - The $decisions array is sparsely populated with situations where the decision would change
	 * - In order to come to correct decision we may need to have sorted the opinions ( ASC ).
	 */
	public function consider( $current_decision ) {
		$decisions = array( "AOAM" => "AM"
		                  , "AOBM" => "BM"
											, "AOCM" => "CM"
											, "AOCO" => "CO"
											, "AMBM" => "BM"  
		                  , "AMCM" => "CM"
											, "BOAO" => "AO"
											, "BOAM" => "AM"
											, "BOBM" => "BM"
											, "BOCO" => "AO"
											, "BOCM" => "CM"
											, "BMAM" => "BM"
											, "BMCM" => "CM" // Oops
											, "COAO" => "CO" // Stay with CO once chosen
											, "COAM" => "AM"
											, "COBO" => "AO"
											, "COBM" => "BM"
											, "COCM" => "CM"
											, "CMAM" => "CM" // Oops
											, "CMBM" => "CM" // Oops
											);
		$next_opinion = $this->get_preferred_editor();
		$next_opinion .= $this->get_mandatory();
		
		$current_opinion=  $this->get_opinion();
	  // echo "cD: $current_decision $next_opinion $current_opinion";
		
		$new_decision = bw_array_get( $decisions, $current_decision . $next_opinion, $current_decision );
		
		return $new_decision;
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
