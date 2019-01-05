<?php

/**
 * (C) Copyright Bobbing Wide 2018
 * @package: oik-block
 */
 
/**
 * Implements [blocks] shortcode for oik-block
 * 
 * Dependent upon the oik base plugin and Gutenberg
 * Primarily intended for use in a sidebar this shortcode will display
 * the blocks that are being used in a summary table.
 *
 * We'll need this logic for parsing the content anyway.
 * 
 * type | static/dynamic | dynamic link
 * ----- | ---------- | ---------------
 * 
 *
 * 
 *
 * @param array $atts Shortcode parameters - none so far
 * @param string $content embedded content - not expected
 * @param string $tag shortcode used
 * @return string the generated HTML
 */
function oik_block_blocks( $atts, $content, $tag ) {

	$post_id = oik_block_get_post_id( $atts );
	if ( $post_id ) {
		if ( is_single( $post_id ) ) {
			$post = get_post( $post_id );
			h4( "Blocks", "widget-title widgettitle" );
			e( $post->post_title );
			br();	
			$post->post_content = str_replace( "\r", "\\r", $post->post_content );
			$post->post_content = str_replace( "\n", "\\n", $post->post_content );
			$post->post_content = str_replace( "\t", "\\t", $post->post_content );
			e( esc_html( $post->post_content ) );
			//bw_trace2( $post->post
			//oik_block_registered_blocks();
		
			//oik_block_report_blocks( $post->post_content );
		}
	}
	
	return( bw_ret() );

}

/**
 */

function oik_block_get_post_id( $atts ) {
  $post_id = bw_array_get( $atts, "id", null );
  if ( $post_id ) {
    $single = true;
  } else { 
    $post_id = bw_current_post_id();
    if ( $post_id === bw_global_post_id() ) {
      $single = true;
    } else {
      $single = is_single( $post_id );
    }   
  }
	if ( $single ) { 
		$single = $post_id;
	}
	return $single;
}


/**
 * This associative array 
 * lists the registered blocks
 * You can't tell from the attributes
 * which are ID fields. You have to infer it
 * 
 * core/block uses ref to locate the wp_block post
 * core/latest-posts 
 * 
 */
function oik_block_registered_blocks() {


	//WP_Block_Registry::get_all_registered
	if ( function_exists( "gutenberg_prepare_blocks_for_js" ) ) {
		oik_require( "lib/client-assets.php", "gutenberg" );
		$blocks = gutenberg_prepare_blocks_for_js();
		bw_trace2( $blocks, "blocks", false );
	} else {
		p( "New Editor not active!", "warning" );
	}
	
}

/*


    [core/block] => Array
        (
            [attributes] => Array
                (
                    [ref] => Array
                        (
                            [type] => number
                        )

                )

        )

    [core/latest-posts] => Array
        (
            [attributes] => Array
                (
                    [categories] => Array
                        (
                            [type] => string
                        )

                    [postsToShow] => Array
                        (
                            [type] => number
                            [default] => 5
                        )

                    [displayPostDate] => Array
                        (
                            [type] => boolean
                            [default] => 
                        )

                    [layout] => Array
                        (
                            [type] => string
                            [default] => list
                        )

                    [columns] => Array
                        (
                            [type] => number
                            [default] => 3
                        )

                    [align] => Array
                        (
                            [type] => string
                            [default] => center
                        )

                    [order] => Array
                        (
                            [type] => string
                            [default] => desc
                        )

                    [orderBy] => Array
                        (
                            [type] => string
                            [default] => date
                        )

                )

        )
				
				
*/ 

function oik_block_report_blocks( $content ) {
	$parsed = gutenberg_parse_blocks( $content );
	bw_trace2( $parsed, "parsed" );
	foreach ( $parsed as $block ) {
		$blockName = bw_array_get( $block, "blockName", null );
		$attrs = bw_array_get( $block, 'attrs', null );
		if ( $attrs ) {
			$cattrs = count( $attrs );
		} else {
			$cattrs = null;
		}
		$inner = count( bw_array_get( $block, "innerBlocks", [] ) );
		$htmlLen = strlen( $block['innerHTML'] );
		p( "$blockName: $cattrs: $inner: $htmlLen " );
	}
		
}


/** 
 * Example of the $parsed output
 * Looks like we can have empty array instances? 
 * Not sure why
 * Also don't know where these &nbsp; characters appeared in the oik-block paragraph


    [0] => Array
        (
            [blockName] => core/paragraph
            [attrs] => Array
                (
                    [align] => right
                    [fontSize] => 34
                )

            [innerBlocks] => Array
                (
                )

            [innerHTML] => 
<p style="font-size:34px;text-align:right"> oik-block   </p>

        )

    [1] => Array
        (
            [attrs] => Array
                (
                )

            [innerHTML] => 


        )

    [2] => Array
        (
            [blockName] => core/image
            [attrs] => Array
                (
                    [id] => 21336
                    [align] => center
                )

            [innerBlocks] => Array
                (
                )

            [innerHTML] => 
<figure class="wp-block-image aligncenter"><img src="https://qw/hm/wp-content/uploads/2018/02/oik-banner-772x250.jpg" alt="Image for the oik-block plugin banner" />
    <figcaption><strong>oik-block</strong> - oik-shortcodes for WordPress 5.0 blocks</figcaption>
</figure>

        )

)
*/

