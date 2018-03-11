<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class component_counter {


	public $components;
	
	/**
	 * Constructor for component_counter
	 */
	public function __construct() {
		$this->components = array();
		$this->components['plugins'] = array();
		$this->components['themes'] = array();
	}
	
	/**
	 * Adds a component or increments the count of times found
	 *
	 */
	public function add( $component, $type, $path ) {
		if ( !isset( $this->components[ $type ][ $component ] ) ) {
			$this->components[ $type ][ $component ] = new Component( $component, $type, $path);
		}
		$this->components[ $type ][ $component ]->add(); 
	}
	
	
	/**
	 * Reports the counts and other information for each component
	 */
	public function report() {
		//print_r( $this->components );
		echo "Type,Component,Count,Author,Third Party,Tests" . PHP_EOL;
		$components = 0;
		$total = 0;
		foreach ( $this->components as $type => $data ) {
			foreach ( $data as $component => $component_object ) {
				$count = $component_object->report();
				$total += $count;
				$components++;
			}
		}
		echo "Totals,$components,$total" . PHP_EOL;
	}
	
}
