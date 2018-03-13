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
 * string $observation
 * string $choice_of_action
 * 
 * Opinions are gathered using the 'oik_block_gather_opinions' filter passing $opinions, $post
 
 */

	public string $preferred_editor;
	bool $mandatory;
	string $observation
	string $choice_of_action;
	 
	
	public __construct( $preferred_editor, $mandatory=false, $observation, $choice_of_action="" ) {
	
		$this->preferred_editor = $preferred_editor;
		$this->mandatory = $mandatory;
		$this->observation = $observation;
		$this->choice_of_action = $choice_of_action;
	
	}
	
	public report() {
		gob();
	} 



}
