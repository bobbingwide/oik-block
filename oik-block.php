<?php
/**
 * Plugin Name: oik-block
 * Plugin URI: https://gutenberg.courses
 * Description: Prototype blocks for content originally created with oik shortcodes
 * Author: Herb Miller
 * Author URI: https://herbmiller.me/about/mick
 * Version: 0.0.0
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package oik-block
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*
function oik_block_templates( $args, $post_type ) {

  if ( $post_type == 'post' ) {
    $args['template_lock'] = true;
    $args['template'] = [
      [
        'core/image', [
          'align' => 'left',
        ]
      ],
      [
        'core/paragraph', [
          'placeholder' => 'The only thing you can add',
        ]
      ]
    ];
  }

  return $args;

}
//add_filter( 'register_post_type_args', 'oik_block_templates', 20, 2 );
*/

/**
 * Enqueues block editor JavaScript and CSS
 * 
 * 
 * 
 */
function oik_block_editor_scripts() {
	bw_trace2();
	bw_backtrace();
	
	if ( isset( $_GET['classic-editor'] ) ) {
		gob();
	}
	//gob();

    // Make paths variables so we don't write em twice ;)
    $blockPath = '/assets/js/editor.blocks.js';
    //$blockPath = '/assets/js/dummy.blocks.js';
		
    $editorStylePath = '/assets/css/blocks.editor.css';

    // Enqueue the bundled block JS file
		
    wp_enqueue_script(
        'oik_block-blocks-js',
        plugins_url( $blockPath, __FILE__ ),
        [ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api' ],
        filemtime( plugin_dir_path(__FILE__) . $blockPath )
    );
		

    // Pass in REST URL
    wp_localize_script(
      'oik_block-blocks-js',
      'oik_block_globals',
      [
        'rest_url' => esc_url( rest_url() )
      ]);


    // Enqueue optional editor only styles
		
    wp_enqueue_style(
        'oik_block-blocks-editor-css',
        plugins_url( $editorStylePath, __FILE__),
        [ 'wp-blocks' ],
        filemtime( plugin_dir_path( __FILE__ ) . $editorStylePath )
    );
		
		

}


/**
 * Enqueue block editor JavaScript and CSS
 * 
 
 * It's not the setting of classic-editor that's the problem
 * it's the fact that enqueueing the scripts when we're not in the editor
 * but are loading TinyMCE that we get the problem.
 * 
 * But which of the scripts is to blame!
 * Try knocking out the dependencies one by one.
 * 
 */
function oik_block_frontend_scripts()
{
    $blockPath = '/assets/js/frontend.blocks.js';
    // Make paths variables so we don't write em twice ;)
		
		 bw_backtrace();
		 bw_trace2( $_GET, "_GET" );

		// Don't do Gutenberg stuff when loading the classic editor
		if ( array_key_exists( 'classic-editor', $_GET ) ) {
			remove_filter( 'wp_editor_settings', 'gutenberg_disable_editor_settings_wpautop' );
			//gob();
			return;
		}
		
		
		
    // Enqueue the bundled block JS file
     wp_enqueue_script(
        'oik_block-blocks-frontend-js',
        plugins_url( $blockPath, __FILE__ ),
        [ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api' ],
        filemtime( plugin_dir_path(__FILE__) . $blockPath )
    );
		
		
		


}

function oik_block_frontend_styles() {
    $stylePath = '/assets/css/blocks.style.css';
    // Enqueue frontend and editor block styles
    wp_enqueue_style(
        'oik_block-blocks-css',
        plugins_url($stylePath, __FILE__),
        [ 'wp-blocks' ],
        filemtime(plugin_dir_path(__FILE__) . $stylePath )
    );

}



/**
 * Server rendering contact-form block
 * 
 * @param array $attributes
 * @return string generated HTML
 */
function oik_block_dynamic_block_contact_form( $attributes ) {
	bw_backtrace();
	bw_trace2();
	
	oik_require( "shortcodes/oik-contact-form.php" );
	$html = bw_contact_form( $attributes );
	return $html;

}



/**
 * Server rendering for /blocks/examples/13-dynamic-lat
 */
function oik_block_dynamic_alt_block_render( $attributes ) {

    $posts = wp_get_recent_posts( array(
        'numberposts' => 5,
        'post_status' => 'publish',
    ) );

    if ( count( $posts ) === 0 ) {
        return 'No posts';
    }

    $markup = '<ul>';
    foreach( $posts as $post ) {

      $markup .= sprintf(
          '<li><a class="wp-block-my-plugin-latest-post" href="%1$s">%2$s</a></li>',
          esc_url( get_permalink( $post['ID'] ) ),
          esc_html( get_the_title( $post['ID'] ) )
      );

    }

    return $markup;

}

/**
 * Here we'll implement logic to test whether or not we're going to allow Gutenberg to edit the content
 *
 * If there are no blocks then we use the user's or system options.
 *
 */
function oik_block_gutenberg_can_edit_post_type( $can_edit, $post_type ) {
	bw_trace2();
	//gob();
	return $can_edit;
}

/**
 * Implements actions for "oik_loaded"
 *
 * Now we know it's safe to respond to shortcodes
 */
function oik_block_oik_loaded() {
	add_action( "oik_add_shortcodes", "oik_block_oik_add_shortcodes" );
}

/** 
 * Add our shortcodes
 */
function oik_block_oik_add_shortcodes() {
  bw_add_shortcode( "blocks", "oik_block_blocks", oik_path("shortcodes/oik-blocks.php", "oik-block"), false );
	bw_add_shortcode( "guts", "oik_block_guts", oik_path( "shortcodes/oik-guts.php", "oik-block" ), false );
}


function oik_block_loaded() {

	// Hook scripts and styles functions into enqueue_block_assets hook
	
	add_action('enqueue_block_assets', 'oik_block_frontend_scripts');
	add_action('enqueue_block_assets', 'oik_block_frontend_styles');
	
	// Hook scripts function into block editor hook
	add_action( 'enqueue_block_editor_assets', 'oik_block_editor_scripts' );
	
	add_filter( 'gutenberg_can_edit_post_type', 'oik_block_gutenberg_can_edit_post_type', 10, 2 );
	
	

	add_action( "oik_loaded", "oik_block_oik_loaded" );
	
	oik_block_register_dynamic_blocks();

}

/**
 * Registers action/filter hooks for oik's dynamic blocks
 */
function oik_block_register_dynamic_blocks() {
	if ( function_exists( "register_block_type" ) ) {
		register_block_type( 'oik-block/contact-form', [  'render_callback' => 'oik_block_dynamic_block_contact_form' ] );
	}
}

oik_block_loaded();





