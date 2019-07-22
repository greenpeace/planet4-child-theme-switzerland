<?php

if ( ! function_exists( 'p4_child_theme_gpch_gutenberg_color_palette' ) ) {

	add_action( 'after_setup_theme', 'p4_child_theme_gpch_gutenberg_color_palette' );

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
					'name'  => esc_html__( 'Greenpeace 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-green-1',
					'color' => '#66cc00',
				),
				array(
					'name'  => esc_html__( 'Greenpeace 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-green-2',
					'color' => '#8bcc4a',
				),
				array(
					'name'  => esc_html__( 'Greenpeace 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-green-3',
					'color' => '#abd878',
				),
				array(
					'name'  => esc_html__( 'Agriculture 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-agriculture-1',
					'color' => '#91721e',
				),
				array(
					'name'  => esc_html__( 'Agriculture 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-agriculture-2',
					'color' => '#c8b98f',
				),
				array(
					'name'  => esc_html__( 'Agriculture 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-agriculture-3',
					'color' => '#3aa974',
				),
				array(
					'name'  => esc_html__( 'Arctic 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-arctic-1',
					'color' => '#1ebcef',
				),
				array(
					'name'  => esc_html__( 'Arctic 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-arctic-2',
					'color' => '#ddf1fd',
				),
				array(
					'name'  => esc_html__( 'Arctic 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-arctic-3',
					'color' => '#00b3c4',
				),
				array(
					'name'  => esc_html__( 'Climate 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-climate-1',
					'color' => '#cd1719',
				),
				array(
					'name'  => esc_html__( 'Climate 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-climate-2',
					'color' => '#eaccbb',
				),
				array(
					'name'  => esc_html__( 'Climate 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-climate-3',
					'color' => '#cccccc',
				),
				array(
					'name'  => esc_html__( 'Chemical 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-chemical-1',
					'color' => '#4a81b4',
				),
				array(
					'name'  => esc_html__( 'Chemical 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-chemical-2',
					'color' => '#a5c0da',
				),
				array(
					'name'  => esc_html__( 'Chemical 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-chemical-3',
					'color' => '#ff3893',
				),
				array(
					'name'  => esc_html__( 'Fleet 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-fleet-1',
					'color' => '#007799',
				),
				array(
					'name'  => esc_html__( 'Fleet 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-fleet-2',
					'color' => '#80bbcc',
				),
				array(
					'name'  => esc_html__( 'Fleet 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-fleet-3',
					'color' => '#1a1a1a',
				),
				array(
					'name'  => esc_html__( 'Forest 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-forest-1',
					'color' => '#26774e',
				),
				array(
					'name'  => esc_html__( 'Forest 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-forest-2',
					'color' => '#b3cfc1',
				),
				array(
					'name'  => esc_html__( 'Forest 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-forest-3',
					'color' => '#d06283',
				),
				array(
					'name'  => esc_html__( 'Ocean 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-ocean-1',
					'color' => '#3290de',
				),
				array(
					'name'  => esc_html__( 'Ocean 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-ocean-2',
					'color' => '#a4c2e7',
				),
				array(
					'name'  => esc_html__( 'Ocean 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-ocean-3',
					'color' => '#1152f0',
				),
				array(
					'name'  => esc_html__( 'Plastic 1', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-plastic-1',
					'color' => '#69c2be',
				),
				array(
					'name'  => esc_html__( 'Plastic 2', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-plastic-2',
					'color' => '#c4e3e1',
				),
				array(
					'name'  => esc_html__( 'Plastic 3', 'planet4-child-theme-switzerland' ),
					'slug'  => 'gp-plastic-3',
					'color' => '#e94e28',
				),
			)
		);
	}
}
