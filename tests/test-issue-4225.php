<?php // (C) Copyright Bobbing Wide 2018

class Tests_issue_4225 extends BW_UnitTestCase {

	/** 
	 * set up logic
	 * 
	 * - ensure any database updates are rolled back
	 */
	function setUp() {
		parent::setUp();
	}
	
	/**
	 * Tests for:
	 * <!--more-->
	 * <!--nextpage-->
	 * <!--noteaser-->
	 *
	 * Is it possible to have a page that validly contains multiple pages and more's
	 * and how would you use it? 
	 */
	function test_get_the_content_page_2() {
	
		//add_filter( "the_content_more_link", "__return_null"  );
		add_filter( "get_the_excerpt", array( $this, "paginated_excerpt" ), 10, 2 );
		$post = $this->dummy_post();
		$GLOBALS['post'] = $post; 
		setup_postdata( $post );
		$this->show_pages();
		//the_post();
		$this->fiddle_globals( 3 );
	 // print_r( $post );
	  $content = get_the_content();
		echo PHP_EOL;
		echo "Content:" . PHP_EOL;
		echo $content;
		
		$excerpt = get_the_excerpt();
		echo PHP_EOL;
		echo "Excerpt:" . PHP_EOL;
		
		echo $excerpt;
		$this->assertNull( $excerpt );
	
	
	}
	
	function paginated_excerpt( $excerpt, $post ) {
		global $more;
		$saved = $more;
		$more = false;
	  $excerpt = get_the_content();
		$more = $saved;
		return $excerpt;
	}
	
	function show_pages() {
		global $pages;
		print_r( $pages );
	}
	
	/**
	 * Allow for WordPress core bug #42814
	 * We need to get the post
	 *  https://github.com/Automattic/jetpack/pull/8510
	 */
	function fiddle_globals( $page_number=1 ) {
		global $page, $more, $preview, $pages, $multipage;
		$page = $page_number;
		$more = true;
		//var_dump( $more );
		//var_dump( $multipage );
		//$more = false;
			
	}
	
	function post_content() {
		$post_content = array();
		$post_content[] = "Page 1 excerpt";
		$post_content[] = "<!--more more-->";
		$post_content[] = "Page 1 content";
		$post_content[] = "<!--nextpage-->";
		$post_content[] = "Page 2 excerpt";
		$post_content[] = "<!--more again-->";
		$post_content[] = "Page 2 content";
		$post_content[] = "<!--nextpage-->";
		$post_content[] = "Page 3 excerpt";
		$post_content[] = "<!--more third-->";
		$post_content[] = "Page 3 content";
		$content = implode( "\n", $post_content );
		return $content;
	}
	
	function dummy_post() {
		$content = $this->post_content();
		$args = array( 'post_type' => 'page', 'post_title' => 'post title', 'post_excerpt' => null, 'post_content' => $content );
		$post = self::factory()->post->create_and_get( $args );
		$post->post_excerpt = null;
		//add_post_meta( $id, "field", "felt value", true );
		//add_post_meta( $id, "wrong-field", "wrong value should not be displayed", true );
		return $post;
	
	}

}

