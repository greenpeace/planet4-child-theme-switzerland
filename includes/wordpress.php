<?php

/**
 * Filter to show more search results when adding pages to the menu
 * @param $q
 *
 * @return mixed
 */
function gpch_search_more_menu_pages( $q ) {
	if ( isset( $_POST['action'] ) && $_POST['action'] == "menu-quick-search" && isset( $_POST['menu-settings-column-nonce'] ) ) {
		if ( is_a( $q->query_vars['walker'], 'Walker_Nav_Menu_Checklist' ) ) {
			$q->query_vars['posts_per_page'] = 100;
		}
	}

	return $q;
}

add_action( 'pre_get_posts', 'gpch_search_more_menu_pages', 10, 2 );
