<?php

if ( ! function_exists( 'p4_child_theme_gpch_whitelist_blocks' ) ) {
	add_filter( 'allowed_block_types_all', 'p4_child_theme_gpch_blocks_allowlist', 10, 2 );

	/**
	 * Allowlist for Gutenberg Blocks
	 *
	 * @param $allowed_blocks
	 * @param $post
	 *
	 * @return array
	 */
	function p4_child_theme_gpch_blocks_allowlist( $allowed_blocks, $context ) {
		$additional_blocks = [
			'acf/p4-gpch-block-form-progress-bar',
			'acf/p4-gpch-block-form-counter-text',
			'acf/p4-gpch-block-donation-progress-bar',
			'acf/p4-gpch-block-gpch-events',
			'acf/p4-gpch-block-spacer',
			'acf/p4-gpch-block-word-cloud',
			'social-warfare/social-warfare',
			'planet4-gpch-plugin-blocks/p2p-share',
			'planet4-gpch-tamaro/tamaro-widget',
		];

		$allowed_blocks = array_merge($allowed_blocks, $additional_blocks);

		if ( ! is_object( $context->post ) || ! property_exists( $context->post, 'post_type' ) ) {
			return $allowed_blocks;
		} else {
			if ( $context->post->post_type === 'page' ) { // Block types only for pages
				$additional_blocks_page = [
					'acf/p4-gpch-block-taskforce',
					'planet4-gpch-plugin-blocks/dreampeace-cover',
					'planet4-gpch-plugin-blocks/dreampeace-slide',
				];

				$allowed_blocks = array_merge($allowed_blocks, $additional_blocks_page);
			} else if ( $context->post->post_type === 'post' ) { // block types only for posts
				$additional_blocks_post = [];

				$allowed_blocks = array_merge( $allowed_blocks, $additional_blocks_post );
			} else if ( $context->post->post_type === 'gpch_event' ) { // block types only for gpch_events
				$additional_blocks_post = [];

				$allowed_blocks = array_merge( $allowed_blocks, $additional_blocks_post );
			}

			return $allowed_blocks;
		}
	}
}
