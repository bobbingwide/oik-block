<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2020
 * @package oik-block
 */

/**
 * Syntax: oikwp oik-block-counter.php subcommand url=domain path=path
 *
 * Main routine to count the number of times each block type is used

 *
 */
oik_block_counter_loaded();


/**
 * Function to invoke when oik-block-opinions is loaded
 *
 * Waits until "run_oik-block-opinions.php" action is run.
 *
 */
function oik_block_counter_loaded() {
	add_action( "run_oik-block-counter.php", "oik_block_counter_run" );
}

/**
 * Runs oik-block-opinions
 */
function oik_block_counter_run() {
	$subcommand = oik_batch_query_value_from_argv( 1, null );
	$post_type_or_id = oik_batch_query_value_from_argv( 2, 'block' );
	oik_block_counter_subcommand( $subcommand, $post_type_or_id );

}

function oik_block_counter_subcommand( $subcommand, $post_type_or_id ) {
	oik_require( 'admin/class-oik-block-counter-subcommands.php', 'oik-block');
	$counter_subcommands = new oik_block_counter_subcommands( $subcommand, $post_type_or_id );
}

