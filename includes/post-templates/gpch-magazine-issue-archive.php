<?php
// phpcs:ignoreFile

/**
 * The Template for displaying the Issue taxonomy archive (masonry overview page).
 *
 * @package  WordPress
 * @subpackage  Timber
 */

use Timber\Timber;

$context = Timber::context();
$term = get_queried_object();

$context['issue'] = array(
	'name' => $term->name,
	'title' => get_field('issue_title', 'term_' . $term->term_id),
	'title_image' => get_field('issue_title_image', 'term_' . $term->term_id),
	'publication_date' => get_field('issue_publication_date', 'term_' . $term->term_id),
);

$timber_posts = Timber::get_posts();
$context['articles'] = array_map(
	function ($timber_post) {
		$timber_post->layout_size = get_field('magazine_layout_size', $timber_post->ID) ?: '1x1';
		$section_terms = get_the_terms($timber_post, 'gpch_magazine_section');
		$timber_post->sections = is_array($section_terms) ? array_map(
			function ($section_term) {
				return array(
					'name' => $section_term->name,
					'link' => get_term_link($section_term),
				);
			},
			$section_terms
		) : array();

		return $timber_post;
	},
	iterator_to_array($timber_posts)
);

Timber::render('taxonomy-gpch_magazine_issue.twig', $context);
