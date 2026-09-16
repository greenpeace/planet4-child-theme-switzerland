<?php
// Leave this file in Planet4 code style for easier upstream comparison
// phpcs:ignoreFile

global $post;

/**
 * The Template for displaying single Magazine Article posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use P4\MasterTheme\Context;
use Timber\Timber;

// Initializing variables.

$context         = Timber::context();
$timber_post     = Timber::get_post( $post->ID );
$context['post'] = $timber_post;

// Set Navigation Issues links.
$timber_post->set_issues_links();

$page_meta_data = get_post_meta($timber_post->ID);
$page_meta_data = array_map(fn($v) => reset($v), $page_meta_data);
$page_terms_data = get_the_terms($timber_post, 'p4-page-type');
$page_terms_data = is_array($page_terms_data) ? reset($page_terms_data) : null;
$context['background_image'] = $page_meta_data['p4_background_image_override'] ?? '';
$context['page_type'] = $page_terms_data->name ?? '';
$context['page_term_id'] = $page_terms_data->term_id ?? '';
$context['custom_body_classes'] = 'white-bg';
$context['page_type_slug'] = $page_terms_data->slug ?? '';
$context['social_accounts'] = $timber_post->get_social_accounts($context['footer_social_menu'] ?: []);
$context['page_category'] = 'Post Page';
$context['post_tags'] = implode(', ', $timber_post->tags());
$context['post_categories'] = implode(', ', $timber_post->categories());
// We need the explode because we want to remove "+00:00" at the end of the string.
$context['page_date'] = explode('+', get_the_date('c', $timber_post->ID))[0];
$context['old_posts_archive_notice'] = $timber_post->get_old_posts_archive_notice();
$context['archive_link'] = get_post_type_archive_link( 'gpch_magazinearticle' );

// Issue: only the first assigned term is used (only one Issue should be selected per article).
$issue_terms = get_the_terms($timber_post, 'gpch_magazine_issue');
$issue_term = is_array($issue_terms) ? reset($issue_terms) : null;
$context['issue'] = $issue_term ? array(
	'name' => $issue_term->name,
	'link' => get_term_link($issue_term),
	'title' => get_field('issue_title', 'term_' . $issue_term->term_id),
	'title_image' => get_field('issue_title_image', 'term_' . $issue_term->term_id),
	'publication_date' => get_field('issue_publication_date', 'term_' . $issue_term->term_id),
	'introduction' => get_field('issue_introduction', 'term_' . $issue_term->term_id),
) : null;

// Section (Rubrik): only the first assigned term is used (only one Section should be selected per article).
$section_terms = get_the_terms($timber_post, 'gpch_magazine_section');
$section_term = is_array($section_terms) ? reset($section_terms) : null;
$context['section'] = $section_term ? array(
	'name' => $section_term->name,
	'link' => get_term_link($section_term),
) : null;

Context::set_og_meta_fields($context, $timber_post);
Context::set_campaign_datalayer($context, $page_meta_data);
Context::set_utm_params($context, $timber_post);
Context::set_reading_time_datalayer($context, $timber_post);

$context['filter_url'] = add_query_arg(
	[
		's' => ' ',
		'orderby' => 'relevant',
		'f[ptype][' . $context['page_type'] . ']' => $context['page_term_id'],
	],
	get_home_url()
);

Context::set_p4_blocks_datalayer($context, $timber_post);

if (post_password_required($timber_post->ID)) {
	// Password protected form validation.
	$context['is_password_valid'] = $timber_post->is_password_valid();

	// Hide the post title from links to the extra feeds.
	remove_action('wp_head', 'feed_links_extra', 3);

	$context['login_url'] = wp_login_url();

	do_action('enqueue_google_tag_manager_script', $context);
	Timber::render('single-password.twig', $context);
} else {
	do_action('enqueue_google_tag_manager_script', $context);
	Timber::render(
		[ 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig' ],
		$context
	);
}
