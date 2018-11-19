<?php
/**
 * Plugin Name: oik-block
 * Plugin URI: https://www.oik-plugins.com/oik-plugins/oik-block
 * Description: WordPress Gutenberg blocks for oik shortcodes
 * Author: Herb Miller
 * Author URI: https://herbmiller.me/about/mick
 * Version: 0.0.0-alpha-20181118
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
 * Notes:
 * - This routine shouldn't be invoked when using the classic-editor
 * - It should only enqueue scripts for the Gutenberg editor
 * - Not in other admin areas 
 * 
 * @TODO Check purpose of the wp_localize_script !
 */
function oik_block_editor_scripts() {
	bw_trace2();
	bw_backtrace();
	
	if ( isset( $_GET['classic-editor'] ) ) {
		gob();
	}

	if ( doing_filter( "replace_editor" ) ) {
		wp_enqueue_script( 'oik_block-blocks-js' );
		// Pass in REST URL
		wp_localize_script(
			'oik_block-blocks-js',
			'oik_block_globals',
			[
				'rest_url' => esc_url( rest_url() )
			]);
	}
	// Enqueue optional editor only styles
	$editorStylePath = 'blocks/build/css/blocks.editor.css';
		
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
    $blockPath = 'blocks/build/js/frontend.blocks.js';
    // Make paths variables so we don't write em twice ;)
		
		 bw_backtrace();
		 bw_trace2( $_GET, "_GET" );

		// Don't do Gutenberg stuff when loading the classic editor
		if ( array_key_exists( 'classic-editor', $_GET ) ) {
			remove_filter( 'wp_editor_settings', 'gutenberg_disable_editor_settings_wpautop' );
			//gob();
			return;
		}
		
		if ( is_admin() ) {
			return;
		}
		
		
    // Enqueue the bundled block JS file
     wp_enqueue_script(
        'oik_block-blocks-frontend-js',
        plugins_url( $blockPath, __FILE__ ),
        //[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api' ],
				
					[ 'wp-blocks', 'wp-element' ],
        filemtime( plugin_dir_path(__FILE__) . $blockPath )
    );
		
		
		


}

function oik_block_frontend_styles() {
    $stylePath = 'blocks/build/css/blocks.style.css';
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
	
	if ( did_action( "oik_loaded" ) ) {
		oik_require( "shortcodes/oik-contact-form.php" );
		$html = bw_contact_form( $attributes );
	} else {
		$html = "The Contact form block requires the oik plugin";
	}
	return $html;

}

/**
 * Server rendering dynamic CSS block with content
 * 
 * Assumes that the oik-css plugin is installed.
 * The plugin doesn't need to be activated.
 * 
 * @param array $attributes
 * @return string generated HTML
 */
function oik_block_dynamic_block_css( $attributes ) {
	//bw_backtrace();
	$content = bw_array_get( $attributes, "css", null );
	bw_trace2( $content, "Content" );
	//$content = oik_block_fetch_dynamic_content( "wp:oik-block/css" );
	oik_require( "shortcodes/oik-css.php", "oik-css" );
	$html = oik_css( $attributes, $content );
	return $html;
}


/**
 * Server rendering dynamic CSV block
 * 
 * Assumes that the oik-bob-bing-wide plugin is installed.
 * The plugin doesn't need to be activated.
 * 
 * @param array $attributes
 * @return string generated HTML
 */
function oik_block_dynamic_block_csv( $attributes ) {
	//bw_backtrace();
	$content = bw_array_get( $attributes, "content", null );
	bw_trace2( $content, "Content" );
	oik_require( "shortcodes/oik-csv.php", "oik-bob-bing-wide" );
	$html = bw_csv( $attributes, $content );
	return $html;
}

/**
 * Server rendering dynamic shortcode block
 *
 * @TODO Find out how to invoke the selected shortcode
 *
 * @param array $attributes
 * @return string generated HTML
 */
function oik_block_dynamic_block_shortcode( $attributes ) {
	oik_require( "shortcodes/oik-shortcode.php", "oik-block" );
	$html = oik_block_shortcode_block( $attributes );
	return $html;
	
}

/**
 * Returns the content of the dynamic block
 * 
 * This is a quick and dirty hack while we're waiting on a fix for Gutenberg issue #5760
 *
 * Assumptions:
 * - The block doesn't contain nested blocks of the same name
 * - The block has an end block marker ( e.g. <!-- /wp:oik-block/css --> )
 * 
 * Supports:
 * - Multiple blocks of the same name
 * - Should support attributes... 
 * 
 * 
 * @param string $blockname - of the form wp:prefix/block e.g. wp:oik-block/css
 * @return string the dynamic content for the block
 */ 
function oik_block_fetch_dynamic_content( $blockname ) {
	static $content = null;
	if ( null === $content ) {
		$content = null;
		$post = get_post();
		if ( $post ) {
			$content = $post->post_content;
		}
	}
	if ( $content ) {
		$start = strpos( $content, "<!-- " . $blockname );
		$content = substr( $content, $start + strlen( $blockname ) + 4 );
		$end_comment = strpos( $content, " -->" );
		$content = substr( $content, $end_comment + 4 );
		$end = strpos( $content, "<!-- /" . $blockname );
		$block_content = substr( $content, 0, $end );
		$content = substr( $content, $end + strlen( $blockname ) + 11 );
		bw_trace2( $content, "content" );
	}
	return $block_content;
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
 * Implements "gutenberg_can_edit_post_type" for oik-blocks 
 * 
 * Here we'll implement logic to test whether or not we're going to allow Gutenberg to edit the content
 *
 * If the post type doesn't support revisions then we may not want to use Gutenberg,
 * but this shouldn't prevent us from allowing the user to edit the content using the block editor.
 * Disabling the test for now. Herb 2018/04/04
 *
 */
function oik_block_gutenberg_can_edit_post_type( $can_edit, $post_type ) {
	bw_trace2();
	//$can_edit = post_type_supports( $post_type, "revisions" );
	return $can_edit;
}

/**
 * Implements actions for "oik_loaded"
 *
 * Now we know it's safe to respond to shortcodes
 */
function oik_block_oik_loaded() {
	add_action( "oik_add_shortcodes", "oik_block_oik_add_shortcodes" );
	add_filter( "oik_block_query_inline_shortcodes", "oik_block_query_inline_shortcodes" );
	add_filter( "oik_block_query_incompatible_shortcodes", "oik_block_query_incompatible_shortcodes" );
}

/** 
 * Add our shortcodes
 */
function oik_block_oik_add_shortcodes() {
  bw_add_shortcode( "blocks", "oik_block_blocks", oik_path("shortcodes/oik-blocks.php", "oik-block"), false );
	bw_add_shortcode( "guts", "oik_block_guts", oik_path( "shortcodes/oik-guts.php", "oik-block" ), false );
	bw_add_shortcode( "content", "oik_block_content", oik_path( "shortcodes/oik-content.php", "oik-block" ), false );
}


function oik_block_loaded() {

	// Hook scripts and styles functions into enqueue_block_assets hook
	
	add_action('enqueue_block_assets', 'oik_block_frontend_scripts');
	add_action('enqueue_block_assets', 'oik_block_frontend_styles');
	
	// Hook scripts function into block editor hook
	add_action( 'enqueue_block_editor_assets', 'oik_block_editor_scripts' );
	
	add_filter( 'gutenberg_can_edit_post_type', 'oik_block_gutenberg_can_edit_post_type', 10, 2 );
	
	

	add_action( "oik_loaded", "oik_block_oik_loaded" );
	add_action( "plugins_loaded", "oik_block_plugins_loaded", 100 );
	
	add_action( "init", "oik_block_register_dynamic_blocks" );
	
	
	
  if ( !defined('DOING_AJAX') ) {
    add_action( "save_post", "oik_block_save_post", 10, 3 );
		add_action( 'add_meta_boxes', 'oik_block_add_meta_boxes', 10, 2 );
    //add_action( "edit_attachment", "oik_clone_edit_attachment", 10, 1 );
    //add_action( "add_attachment", "oik_clone_add_attachment", 10, 1 );
  }

}

/**
 * Registers action/filter hooks for oik's dynamic blocks
 *
 * We have to do this during init, which comes after _enqueue_ stuff
 *
 script, style, editor_script, and editor_style
 */
function oik_block_register_dynamic_blocks() {
	if ( function_exists( "register_block_type" ) ) {
		oik_block_register_editor_scripts(); 
		oik_block_boot_libs();
		register_block_type( 'oik-block/contact-form', 
												[  'render_callback' => 'oik_block_dynamic_block_contact_form' 
												,	'editor_script' => 'oik_block-blocks-js'
												, 'editor_style' => null
												, 'script' => null
												, 'style' => null
												] );
		register_block_type( 'oik-block/css', [ 'render_callback' => 'oik_block_dynamic_block_css' ] );
		register_block_type( 'oik-block/csv', [ 'render_callback' => 'oik_block_dynamic_block_csv' ] );
		register_block_type( 'oik-block/dummy', 
												[ 'render_callback' => 'oik_block_dummy' 
												,	'editor_script' => 'oik_block-dummy-js'
												, 'script' => 'oik_block-dummy-js'
												]
											 );
		register_block_type( 'oik-block/shortcode-block', [ 'render_callback' => 'oik_block_dynamic_block_shortcode' ] );
												 
	}
}

/**
 * Registers the scripts we'll need	for the editor
 * 
 * Not sure why we'll need Gutenberg scripts for the front-end.
 * But we might need Javascript stuff for some things, so these can be registered here.
 *
 * Dependencies were initially 
 * `[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-api' ]`
 *
 * why do we need the dependencies?
 */
function oik_block_register_editor_scripts() {
	bw_trace2();
	bw_backtrace();
	
	$scripts = array( 'oik_block-blocks-js' => 'blocks/build/js/editor.blocks.js' 
									, 'oik_block-dummy-js' => '/blocks/build/js/dummy.blocks.js'
									);
	foreach ( $scripts as $name => $blockPath ) {
		wp_register_script( $name,
			plugins_url( $blockPath, __FILE__ ),
			// [],
			[ 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ],
			filemtime( plugin_dir_path(__FILE__) . $blockPath )
		);
	}
	
}

/**
 * Implements 'plugins_loaded' action for oik-block
 * 
 * Prepares use of shared libraries if this has not already been done.
 */
function oik_block_plugins_loaded() {
	oik_block_boot_libs();
	oik_require_lib( "bwtrace" );
}

/**
 * Boot up process for shared libraries
 * 
 * ... if not already performed
 */
function oik_block_boot_libs() {
	if ( !function_exists( "oik_require" ) ) {
		$oik_boot_file = __DIR__ . "/libs/oik_boot.php";
		$loaded = include_once( $oik_boot_file );
	}
	oik_lib_fallback( __DIR__ . "/libs" );
}

/** 
 * Implements "add_meta_boxes" for oik-block 
 *
 * Adds the meta box to enable the user to set the preferred editor for the post
 * 
 */
function oik_block_add_meta_boxes( $post_type, $post ) {

  //$clone = post_type_supports( $post_type, "clone" );
  //if ( $clone ) {
	if ( function_exists( 'bw_as_array')) {

		oik_require( "admin/oik-block-meta-box.php", "oik-block" );

		add_meta_box( 'oik_block', __( "Editor selection", 'oik-block' ), 'oik_block_meta_box', $post_type, 'normal', 'default' );
	}
}

/**
 * Implements save for the oik_block meta box
 *
 * We invoke the logic as a lazy function.
 *
 * @param ID $id - the ID of the post being updated
 * @param post $post - the post object
 * @param bool $update - true more often than not
 */
function oik_block_save_post( $id, $post, $update ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		// Ignore autosaves
	} else {
		oik_require( "admin/oik-block-save-post.php", "oik-block" );
		oik_block_lazy_save_post( $id, $post, $update );
	}
}

/**
 * Queries inline shortcodes for oik and related plugins
 * 
 * @TODO Determine if we need to know the component for the shortcode.
 * This may help us decide whether or not to deactivate the plugin
 * See also the schunter plugin.
 * 
 * @param array $inline_shortcodes
 * @return array $inline_shortcodes
 */ 
function oik_block_query_inline_shortcodes( $inline_shortcodes ) {
	oik_require( "converter/class-shortcode-converter.php", "oik-block" );
	$shortcode_converter = new Shortcode_converter();
	$inline_shortcodes = $shortcode_converter->query_inline_shortcodes( $inline_shortcodes );
	return $inline_shortcodes;
}


/**
 * Queries incompatible shortcodes for oik and related plugins
 * 
 * @TODO Determine if we need to know the component for the shortcode.
 * This may help us decide whether or not to deactivate the plugin
 * See also the schunter plugin.
 * 
 * @param array $inline_shortcodes
 * @return array $inline_shortcodes
 */ 
function oik_block_query_incompatible_shortcodes( $incompatible_shortcodes ) {
	oik_require( "converter/class-shortcode-converter.php", "oik-block" );
	$shortcode_converter = new Shortcode_converter();
	$incompatible_shortcodes = $shortcode_converter->query_incompatible_shortcodes( $incompatible_shortcodes );
	return $incompatible_shortcodes;
}

oik_block_loaded();





