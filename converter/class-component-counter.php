<?php 

/**
 * @copyright (C) Bobbing Wide 2018
 * @package oik-block
 */

class component_counter {


	public $components;
	
	public function __construct() {
		$this->components = array();
		$this->components['plugins'] = array();
		$this->components['themes'] = array();
	}
	
	public function add( $component, $type ) {
		if ( !isset( $this->components[ $type ][ $component ] ) ) {
			$this->components[ $type ][ $component ] = 0;
		}
		$this->components[ $type ][ $component ] += 1;
	}

	public function report() {
		//print_r( $this->components );
		echo "Type,Component,Count" . PHP_EOL;
		$components = 0;
		$total = 0;
		foreach ( $this->components as $type => $data ) {
			foreach ( $data as $component => $count ) {
				echo "$type,$component,$count" . PHP_EOL;
				$total += $count;
				$components++;
			}
		}
		echo "Totals,$components,$total" . PHP_EOL;
	}
	
}
