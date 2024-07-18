<?php

/**
 * Filter to show more search results when adding pages to the menu
 *
 * @param WP_Query $q The current WP Query object.
 *
 * @return WQ_Query $q
 */
function gpch_search_more_menu_pages( $q ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'menu-quick-search' && isset( $_POST['menu-settings-column-nonce'] ) ) {
		if ( is_a( $q->query_vars['walker'], 'Walker_Nav_Menu_Checklist' ) ) {
			$q->query_vars['posts_per_page'] = 100;
		}
	}

	return $q;
}

add_action( 'pre_get_posts', 'gpch_search_more_menu_pages', 10, 2 );
