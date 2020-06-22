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
$post            = Timber::query_post( false, 'P4_Post' );
$context['post'] = $post;

// Set Navigation Issues links.
$post->set_issues_links();

// Get the cmb2 custom fields data
// Articles block parameters to populate the articles block
// p4_take_action_page parameter to populate the take action boxout block
// Author override parameter. If this is set then the author profile section will not be displayed.
$page_meta_data  = get_post_meta( $post->ID );
$page_terms_data = get_the_terms( $post, 'p4-page-type' );
//$context['background_image']    = $page_meta_data['p4_background_image_override'][0] ?? '';
$take_action_page = $page_meta_data['p4_take_action_page'][0] ?? '';
//$context['page_type']           = $page_terms_data[0]->name ?? '';
//$context['page_term_id']        = $page_terms_data[0]->term_id ?? '';
//$context['page_category']       = 'Post Page';
//$context['page_type_slug']      = $page_terms_data[0]->slug ?? '';
//$context['social_accounts']     = $post->get_social_accounts( $context['footer_social_menu'] );
$context['og_title']            = $post->get_og_title();
$context['og_description']      = $post->get_og_description();
$context['og_image_data']       = $post->get_og_image();
$context['custom_body_classes'] = 'white-bg';

$context['post_tags'] = implode( ', ', $post->tags() );

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
