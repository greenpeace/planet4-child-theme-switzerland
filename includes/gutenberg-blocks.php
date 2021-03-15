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
			'core/block', // needed for reusable blocks to work
			'core/paragraph',
			'core/heading',
			'core/image',
			//'core/gallery', // functionality replaced by P4 galleries
			'core/list',
			'core/quote',
			'core/audio',

			'core/cover',
			'core/file',
			//'core/video', // Not needed, we only allow embedded video
			'core/preformatted',
			//'core/code', // functionality not needed and not styled
			'core/html',
			'core/table',
			//'core/pullquote', // removed, normal quote element is available
			//'core/verse', // removed, not needed, not styled
			'core/buttons',
			//'core/media-text' // removed, not needed
			//'core/more', // removed, not needed
			//'core/nextpage', // removed, not needed,
			'core/separator',
			//'core/spacer', // removed, we provide our own spacer, that's not as configurable
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
			'core-embed/tiktok',
			'acf/p4block-gallery-3column',
			'acf/p4block-gallery-grid',
			'acf/p4block-gallery-slider',
			'acf/p4block-social-facebook-page',
			// 'acf/p4block-social-oembed', // removed, functionality covered by standard Gutenberg blocks
			'acf/p4block-split-two-columns',
			'acf/p4-gpch-block-form-progress-bar',
			'acf/p4-gpch-block-form-counter-text',
			'acf/p4-gpch-block-action-divider',
			'acf/p4-gpch-block-magazine-articles',
			'acf/p4-gpch-block-accordion',
			'acf/p4-gpch-block-taskforce',
			'acf/p4-gpch-block-gpch-jobs',
			'acf/p4-gpch-block-gpch-events',
			'acf/p4-gpch-block-newsletter',
			'acf/p4-gpch-block-spacer',
			'acf/p4-gpch-block-word-cloud',
			'acf/p4-gpch-block-banner-tool',
			'acf/p4block-covers-take-action',
			'acf/raisenow-donation-form',
			'gravityforms/form',
			'social-warfare/social-warfare',
			'social-warfare/click-to-tweet',
			'planet4-gpch-plugin-blocks/bs-bingo'
		);

		if ( $post->post_type === 'page' ) { // Block types only for pages
			$allowed_blocks_page = array(
				'core/freeform',
				// Classic editor, needed for old pages, can be disabled later on
				'core/columns',
				//'gravityforms/mailchimp', // removed, we don't need a Mailchimp block
				'acf/p4block-articles',
				'acf/p4block-header-carousel-classic',
				'acf/p4block-header-carousel-zoom',
				'acf/p4block-covers-campaign',
				'acf/p4block-covers-content',

				// Blocks from planet4-plugin-gutenberg-blocks
				// @see: https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/develop/planet4-gutenberg-blocks.php
				'planet4-blocks/articles',
				'planet4-blocks/carousel-header',
				'planet4-blocks/columns',
				'planet4-blocks/cookies',
				'planet4-blocks/counter',
				'planet4-blocks/covers',
				'planet4-blocks/gallery',
				//'planet4-blocks/happypoint', // not used at GPCH
				'planet4-blocks/media',
				'planet4-blocks/social-media',
				'planet4-blocks/split-two-columns',
				'planet4-blocks/submenu', // not used at GPCH
				'planet4-blocks/take-action-boxout',
				'planet4-blocks/timeline',
			);
			$allowed_blocks      = array_merge( $allowed_blocks_general, $allowed_blocks_page );
		} else if ( $post->post_type === 'post' ) { // block types only for posts
			$allowed_blocks_post = array(
				'core/freeform',
				// Classic editor, needed for old posts
				//'core/columns', // not used in posts

				// Blocks from planet4-plugin-gutenberg-blocks
				// @see: https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/develop/planet4-gutenberg-blocks.php
				'planet4-blocks/counter',
				'planet4-blocks/gallery',
				'planet4-blocks/take-action-boxout',
				'planet4-blocks/timeline',
			);
			$allowed_blocks      = array_merge( $allowed_blocks_general, $allowed_blocks_post );
		} else if ( $post->post_type === 'gpch_event' ) { // block types only for gpch_events
			$allowed_blocks_post = array(
				'core/freeform',
				// Classic editor, needed for old posts
				//'core/columns', // not used in events

				// Blocks from planet4-plugin-gutenberg-blocks
				// @see: https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/develop/planet4-gutenberg-blocks.php
				'planet4-blocks/counter',
				'planet4-blocks/gallery',
				'planet4-blocks/take-action-boxout',
			);
			$allowed_blocks      = array_merge( $allowed_blocks_general, $allowed_blocks_post );
		} else if ( $post->post_type === 'campaign' ) { // block types only for campaign pages
			$allowed_blocks_post = array(
				'core/freeform',
				// Classic editor, needed for old posts
				//'core/columns', // not used in posts

				// Blocks from planet4-plugin-gutenberg-blocks
				// @see: https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/develop/planet4-gutenberg-blocks.php
				// We allow all blocks in these as this content is sometimes imported from other Planet4 sites
				'planet4-blocks/articles',
				'planet4-blocks/carousel-header',
				'planet4-blocks/columns',
				'planet4-blocks/cookies',
				'planet4-blocks/counter',
				'planet4-blocks/covers',
				'planet4-blocks/gallery',
				'planet4-blocks/happypoint',
				'planet4-blocks/media',
				'planet4-blocks/social-media',
				'planet4-blocks/split-two-columns',
				'planet4-blocks/submenu',
				'planet4-blocks/timeline',
			);
		}
		
		return $allowed_blocks;
	}
}

/*
 * Remove the class 'caption-style-blue-overlay' from image blocks
 * https://tickets.greenpeace.ch/view.php?id=323
 */
function gpch_render_block_filter( $block_content, $block ) {
	if ( $block['blockName'] === 'core/image' ) {
		$block_content = str_replace( 'caption-style-blue-overlay', '', $block_content );
	}

	return $block_content;
}

add_filter( 'render_block', 'gpch_render_block_filter', 10, 2 );
