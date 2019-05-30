<?php
/**
 * Template Name: No Header
 * Description: A Page Template without a header (use with care)
 */


use Timber\Timber;

$context        = Timber::get_context();
$post           = new P4_Post();
$page_meta_data = get_post_meta( $post->ID );

// Set GTM Data Layer values.
$post->set_data_layer();
$data_layer = $post->get_data_layer();

$context['post']                        = $post;
$context['header_title']                = is_front_page() ? ( $page_meta_data['p4_title'][0] ?? '' ) : ( $page_meta_data['p4_title'][0] ?? $post->title );
$context['page_category']               = $data_layer['page_category'];
$context['post_tags']                   = implode( ', ', $post->tags() );

$context['og_title']                = $post->get_og_title();
$context['og_description']          = $post->get_og_description();
$context['og_image_data']           = $post->get_og_image();
$context['custom_body_classes']     = 'noheader';

// P4 Campaign/dataLayer fields.
$context['cf_campaign_name'] = $page_meta_data['p4_campaign_name'][0] ?? '';
$context['cf_basket_name']   = $page_meta_data['p4_basket_name'][0] ?? '';
$context['cf_scope']         = $page_meta_data['p4_scope'][0] ?? '';
$context['cf_department']    = $page_meta_data['p4_department'][0] ?? '';

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-page.twig', $context );
} else {
	Timber::render( [ 'page-' . $post->post_name . '.twig', 'page.twig' ], $context );
}
