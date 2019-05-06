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
}
