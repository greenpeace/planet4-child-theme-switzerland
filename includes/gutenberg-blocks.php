<?php

if ( ! function_exists( 'p4_child_theme_gpch_whitelist_blocks' ) ) {
	add_filter( 'allowed_block_types', 'p4_child_theme_gpch_whitelist_blocks', 10, 2 );

	/**
	 * Whitelists Gutenberg Blocks
	 *
	 * @param $allowed_blocks
	 * @param $post
	 *
	 * @return array
	 */
	function p4_child_theme_gpch_whitelist_blocks( $allowed_blocks, $post ) {
		$allowed_blocks_general = array(
			'core/paragraph',
			'core/heading',
			'core/image',
			//'core/gallery', // functionality replaced by P4 galleries
			'core/list',
			'core/quote', // TODO: Styling or removal
			'core/audio',

			'core/cover',
			'core/file',
			//'core/video', // TODO: Decision. Ideally only allow embedded video
			'core/preformatted',
			//'core/code', // functionality not needed and not styled
			'core/html',
			'core/table', // TODO: Styling
			//'core/pullquote', // removed, normal quote element is available
			//'core/verse', // removed, not needed, not styled
			'core/button', // TODO: Styling
			//'core/media-text' // removed, not needed
			//'core/more', // removed, not needed
			//'core/nextpage', // removed, not needed,
			'core/separator', // TODO: Styling
			//'core/spacer', // removed. TODO: Provide our own spacer that has less options
			'core/shortcode',
			//'core/archives', // removed, not needed
			//'core/categories', // removed, not needed
			//'core/latest-comments', // removed, not needed
			//'core/latest-posts' // removed, functionality replaced by P4 article list

			'core/embed',
			'core-embed/twitter',
			'core-embed/youtube',
			'core-embed/facebook',
			'core-embed/instagram',
			'core-embed/wordpress',
			'core-embed/soundcloud',
			'core-embed/spotify',
			'core-embed/flickr',
			'core-embed/vimeo',
			'core-embed/animoto',
			'core-embed/cloudup',
			'core-embed/collegehumor',
			'core-embed/dailymotion',
			'core-embed/funnyordie',
			'core-embed/hulu',
			'core-embed/imgur',
			'core-embed/issuu',
			'core-embed/kickstarter',
			'core-embed/meetup-com',
			'core-embed/mixcloud',
			'core-embed/photobucket',
			'core-embed/polldaddy',
			'core-embed/reddit',
			'core-embed/reverbnation',
			'core-embed/screencast',
			'core-embed/scribd',
			'core-embed/slideshare',
			'core-embed/smugmug',
			'core-embed/speaker',
			'core-embed/ted',
			'core-embed/tumblr',
			'core-embed/videopress',
			'core-embed/wordpress-tv',
			'acf/p4block-gallery-3column',
			'acf/p4block-gallery-grid',
			'acf/p4block-gallery-slider',
			'acf/p4block-social-facebook-page',
			// 'acf/p4block-social-oembed', // removed, functionality covered by standard Gutenberg blocks
			'acf/p4block-split-two-columns',
		);

		if ( $post->post_type === 'page' ) { // Block types only for pages
			$allowed_blocks_page = array(
				'core/freeform', // Classic editor, needed for old pages, can be disabled later on
				'core/columns',
				'acf/p4block-articles',
				'acf/p4block-header-carousel-classic',
				'acf/p4block-header-carousel-zoom',
				'acf/p4block-covers-campaign',
				'acf/p4block-covers-content',
				'acf/p4block-covers-take-action',
			);
			$allowed_blocks      = array_merge( $allowed_blocks_general, $allowed_blocks_page );
		} else if ( $post->post_type === 'post' ) { // block types only for posts
			$allowed_blocks_post = array(
				'core/freeform', // Classic editor, needed for old posts
				//'core/columns', // not used in posts
			);
			$allowed_blocks      = array_merge( $allowed_blocks_general, $allowed_blocks_post );
		}

		return $allowed_blocks;
	}
}

if ( ! function_exists( 'p4_child_theme_gpch_whitelist_blocks_gutenberg_scripts' ) ) {
	/**
	 * Add a script to Gutenberg to control element styles
	 *
	 * @see https://www.billerickson.net/block-styles-in-gutenberg/
	 */
	function p4_child_theme_gpch_whitelist_blocks_gutenberg_scripts() {

		wp_enqueue_script(
			'be-editor',
			get_stylesheet_directory_uri() . '/admin/js/editor.js',
			array( 'wp-blocks', 'wp-dom' ),
			filemtime( get_stylesheet_directory() . '/admin/js/editor.js' ),
			true
		);
	}

	add_action( 'enqueue_block_editor_assets', 'p4_child_theme_gpch_whitelist_blocks_gutenberg_scripts' );
}
