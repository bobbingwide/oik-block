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

//  Exit if accessed directly.
defined('ABSPATH') || exit;

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
 */
function oik_block_editor_scripts()
{

    // Make paths variables so we don't write em twice ;)
    $blockPath = '/assets/js/editor.blocks.js';
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
 */
function oik_block_scripts()
{
    $blockPath = '/assets/js/frontend.blocks.js';
    // Make paths variables so we don't write em twice ;)
    $stylePath = '/assets/css/blocks.style.css';

    // Enqueue the bundled block JS file
    wp_enqueue_script(
        'oik_block-blocks-frontend-js',
        plugins_url( $blockPath, __FILE__ ),
        [ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api' ],
        filemtime( plugin_dir_path(__FILE__) . $blockPath )
    );

    // Enqueue frontend and editor block styles
    wp_enqueue_style(
        'oik_block-blocks-css',
        plugins_url($stylePath, __FILE__),
        [ 'wp-blocks' ],
        filemtime(plugin_dir_path(__FILE__) . $stylePath )
    );

}



/**
 * Server rendering for /blocks/examples/12-dynamic
 */
function oik_block_dynamic_block_render( $attributes ) {

    $recent_posts = wp_get_recent_posts( [
        'numberposts' => 1,
        'post_status' => 'publish',
    ] );
    if ( count( $recent_posts ) === 0 ) {
        return 'No posts';
    }
    $post = $recent_posts[ 0 ];
    $post_id = $post['ID'];
    return sprintf(
        '<p><a class="wp-block-my-plugin-latest-post" href="%1$s">%2$s</a></p>',
        esc_url( get_permalink( $post_id ) ),
        esc_html( get_the_title( $post_id ) )
    );

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


function oik_block_loaded() {

	// Hook scripts function into block editor hook
	//add_action('enqueue_block_assets', 'oik_block_scripts');
	
	// Hook scripts function into block editor hook
	add_action( 'enqueue_block_editor_assets', 'oik_block_editor_scripts' );
	
	
	// Hook server side rendering into render callback
	//register_block_type( 'oik-block/dynamic-alt', [
//			'render_callback' => 'oik_block_dynamic_alt_block_render',
//	] );



	// Hook server side rendering into render callback
//	register_block_type( 'oik-block/dynamic', [
//			'render_callback' => 'oik_block_dynamic_block_render',
//	] );

}

oik_block_loaded();

