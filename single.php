<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Timber;

// Initializing variables.
$context = Timber::get_context();
/**
 * P4 Post Object
 *
 * @var P4_Post $post
 */
$post            = Timber::query_post( false, 'P4_Post' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$context['post'] = $post;


// Set Navigation Issues links.
$post->set_issues_links();

// Get the cmb2 custom fields data
// Articles block parameters to populate the articles block
// p4_take_action_page parameter to populate the take action boxout block
// Author override parameter. If this is set then the author profile section will not be displayed.
$page_meta_data                 = get_post_meta( $post->ID );
$page_meta_data                 = array_map( 'reset', $page_meta_data );
$page_terms_data                = get_the_terms( $post, 'p4-page-type' );
$post_article_types             = get_the_terms( $post, 'gpch-article-type' );
$context['background_image']    = $page_meta_data['p4_background_image_override'] ?? '';
$take_action_page               = $page_meta_data['p4_take_action_page'] ?? '';
$context['page_type']           = $page_terms_data[0]->name ?? '';
$context['page_term_id']        = $page_terms_data[0]->term_id ?? '';
$context['gpch_article_types']  = $post_article_types;
$context['custom_body_classes'] = 'white-bg';
$context['page_type_slug']      = $page_terms_data->slug ?? '';
$context['social_accounts']     = $post->get_social_accounts( $context['footer_social_menu'] );
$context['page_category']       = 'Post Page';
$context['post_tags']           = implode( ', ', $post->tags() );

P4_Context::set_og_meta_fields( $context, $post );
P4_Context::set_campaign_datalayer( $context, $page_meta_data );

$context['filter_url'] = add_query_arg(
	[
		's'                                       => ' ',
		'orderby'                                 => 'relevant',
		'f[ptype][' . $context['page_type'] . ']' => $context['page_term_id'],
	],
	get_home_url()
);


// Build the shortcode for articles block.
if ( 'yes' === $post->include_articles ) {
	$block_attributes = [
		'exclude_post_id'   => $post->ID,
		'post_types'        => array( 75, 77, 80, 194 ),
		'ignore_categories' => true,
	];

	$post->articles = '<!-- wp:planet4-blocks/articles ' . wp_json_encode( $block_attributes, JSON_UNESCAPED_SLASHES ) . ' /-->';

	$post->showArticlesForPostTypes = array(
		'story',
		'story-fr',
		'publikation',
		'publication',
		'hintergrund',
		'article-de-magazine'
	);
}

// Build the shortcode for take action boxout block
// Break the content to retrieve first 2 paragraphs and split the content if the take action page has been defined.
if ( isset( $take_action_boxout_block ) ) {
	$post->take_action_boxout = $take_action_boxout_block;
} elseif ( ! empty( $take_action_page ) ) {
	$post->take_action_page = $take_action_page;

	$block_attributes = [
		'take_action_page' => $take_action_page,
	];

	$post->take_action_boxout = '<!-- wp:planet4-blocks/take-action-boxout ' . wp_json_encode( $block_attributes, JSON_UNESCAPED_SLASHES ) . ' /-->';
}

// Build an arguments array to customize WordPress comment form.
$comments_args = [
	'comment_notes_before' => '',
	'comment_notes_after'  => '',
	'comment_field'        => Timber::compile( 'comment_form/comment_field.twig' ),
	'submit_button'        => Timber::compile( 'comment_form/submit_button.twig' ),
	'title_reply'          => __( 'Leave Your Reply', 'planet4-master-theme' ),
	'fields'               => apply_filters(
		'comment_form_default_fields',
		[
			'author' => Timber::compile( 'comment_form/author_field.twig' ),
			'email'  => Timber::compile( 'comment_form/email_field.twig' ),
		]
	),
];

$context['comments_args']       = $comments_args;
$context['show_comments']       = comments_open( $post->ID );
$context['post_comments_count'] = get_comments(
	[
		'post_id' => $post->ID,
		'status'  => 'approve',
		'type'    => 'comment',
		'count'   => true,
	]
);

if ( post_password_required( $post->ID ) ) {
	$context['login_url'] = wp_login_url();

	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( [
		'single-' . $post->ID . '.twig',
		'single-' . $post->post_type . '.twig',
		'single.twig'
	], $context );
}
