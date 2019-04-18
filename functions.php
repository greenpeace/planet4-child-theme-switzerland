<?php


add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );

function enqueue_child_styles() {
	//$css_creation = filectime( get_stylesheet_directory() . '/style.min.css' );

	//wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.min.css', [], $css_creation );
}


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

/**
 * Add a custom color palette in Gutenberg.
 * Uses the Planet4 color palette found here: https://planet4.greenpeace.org/start/style-guide/#colour-palette
 */
function p4_child_theme_gpch_gutenberg_color_palette() {
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Almost Black', 'planet4-child-theme-switzerland' ),
				'slug'  => 'almost-black',
				'color' => '#1a1a1a',
			),
			array(
				'name'  => esc_html__( 'Gray 2', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gray-2',
				'color' => '#333333',
			),
			array(
				'name'  => esc_html__( 'Gray 3', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gray-3',
				'color' => '#666666',
			),
			array(
				'name'  => esc_html__( 'Gray 4', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gray-4',
				'color' => '#999999',
			),
			array(
				'name'  => esc_html__( 'Gray 5', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gray-5',
				'color' => '#cccccc',
			),
			array(
				'name'  => esc_html__( 'Gray Light', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gray-light',
				'color' => '#e5e5e5',
			),
			array(
				'name'  => esc_html__( 'White', 'planet4-child-theme-switzerland' ),
				'slug'  => 'white',
				'color' => '#ffffff',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Green', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-green',
				'color' => '#66cc00',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Green 2', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-green-2',
				'color' => '#8bcc4a',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Green 3', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-green-3',
				'color' => '#abd878',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Green 4', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-green-4',
				'color' => '#c7e5a5',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Green 5', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-green-5',
				'color' => '#e3f2d2',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Green', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-green',
				'color' => '#003300',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Green 2', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-green-2',
				'color' => '#1b4a1b',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Green 3', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-green-3',
				'color' => '#497a49',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Green 4', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-green-4',
				'color' => '#93b893',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Green 5', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-green-5',
				'color' => '#dbe6d8',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Blue', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-blue',
				'color' => '#61bbfc',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Blue 2', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-blue-2',
				'color' => '#3290de',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Blue 3', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-blue-3',
				'color' => '#61bbfc',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Blue 4', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-blue-4',
				'color' => '#85cafb',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Blue 5', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-blue-5',
				'color' => '#b9e0fb',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Yellow', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-yellow',
				'color' => '#ffd200',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Yellow 2', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-yellow-2',
				'color' => '#ffdb33',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Yellow 3', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-yellow-3',
				'color' => '#ffe466',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Yellow 4', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-yellow-4',
				'color' => '#ffed99',
			),
			array(
				'name'  => esc_html__( 'Greenpeace Traditional Yellow 5', 'planet4-child-theme-switzerland' ),
				'slug'  => 'gp-traditional-yellow-5',
				'color' => '#fff6cc',
			),
		)
	);
}

add_action( 'after_setup_theme', 'p4_child_theme_gpch_gutenberg_color_palette' );

