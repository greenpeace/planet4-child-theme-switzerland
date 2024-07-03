<?php

function gpch_convert_page_headers( $args ) {
	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
	);

	$pages = get_posts( $args );

	print( "Found " . count( $pages ) . " pages\n" );

	$i         = 0;
	$converted = 0;

	foreach ( $pages as $page ) {
		$i++;

		$page_meta_data              = get_post_meta( $page->ID );
		$page_header_hide_page_title = $page_meta_data['p4_hide_page_title_checkbox'][0] ?? '';

		// If the page header is hidden, the page is most likely already converted
		if ( $page_header_hide_page_title == "on" ) {
			print( "Page ID " . $page->ID . ": Already converted, skipping...\n" );
			continue;
		}

		// Find the image ID to use
		$image_id = $page_meta_data['background_image_id'][0] ?? '';

		if ( ! is_int( $image_id ) ) {
			$image_id = get_post_thumbnail_id( $page->ID );
		}

		$image_src = wp_get_attachment_image_src( $image_id );

		// Page title to use
		$page_title = $page_meta_data['p4_title'][0] ?? '';

		if ( empty( $page_title ) ) {
			$page_title = get_the_title( $page->ID );
		}

		$page_header_description          = $page_meta_data['p4_description'][0] ?? '';
		$page_header_button_title         = $page_meta_data['p4_button_title'][0] ?? '';
		$page_header_button_link          = $page_meta_data['p4_button_link'][0] ?? '';
		$page_header_button_link_checkbox = $page_meta_data['p4_button_link_checkbox'][0] ?? '';

		if ( empty( $image_id ) || empty( $page_title ) ) {
			print( "Page ID " . $page->ID . ": Could not find image ID and/or page title" . "\n" );
			continue;
		}

		print( "Page ID " . $page->ID . ": Convert using image ID " . $image_id . " and title: " . $page_title . "\n" );

		// Build Page header Pattern
		$content = '
                <!-- wp:planet4-block-templates/page-header {"mediaPosition":"right"} -->
                <!-- wp:group {"align":"full"} -->
                <div class="wp-block-group alignfull">
                <!-- wp:group {"className":"container"} -->
                <div class="wp-block-group container">
                <!-- wp:media-text {"align":"full","mediaPosition":"right","mediaId": ' . $image_id . ',"mediaLink":"","mediaType":"image","imageFill":false,"className":"is-pattern-p4-page-header is-style-parallax"} -->
                <div class="wp-block-media-text alignfull has-media-on-the-right is-stacked-on-mobile is-pattern-p4-page-header is-style-parallax">

                <div class="wp-block-media-text__content"><!-- wp:group -->
                    <div class="wp-block-group">
                    <!-- wp:heading {"level":1,"placeholder":"Enter title","backgroundColor":"white"} -->
                        <h1 class="wp-block-heading has-white-background-color has-background">
                            ' . $page_title . '
                        </h1>
                    <!-- /wp:heading --></div>
                <!-- /wp:group -->

                ' . ( ! empty( $page_header_description ) ?
				'<!-- wp:paragraph {"placeholder":"Enter description","style":{"typography":{"fontSize":"1.25rem"}}} -->
                        <p style="font-size:1.25rem">
                            ' . preg_replace( "/<p>(.*?)<\/p>/is", "$1", $page_header_description ) . '
                        </p>
                    <!-- /wp:paragraph -->'
				: '' ) . '

                ' . ( ! empty( $page_header_button_link ) ?
				'<!-- wp:buttons -->
                        <div class="wp-block-buttons">
                            <!-- wp:button {"className":"is-style-cta"} /-->
                        </div>
                        <div class="wp-block-button is-style-cta">
                            <a class="wp-block-button__link wp-element-button"
                                href="' . $page_header_button_link . '"
                                target="' . ( $page_header_button_link_checkbox === 'on' ? '_blank' : '' ) . '"
                                rel="noreferrer noopener"
                            >
                                ' . $page_header_button_title . '
                            </a>
                        </div>
                    <!-- /wp:buttons -->'
				: '' ) . '

                </div>
                <figure class="wp-block-media-text__media">
                    <img
                        src="' . $image_src . '"
                        alt=""
                        class="wp-image-' . $image_id . ' size-full"
                    />
                </figure>
                </div>
                <!-- /wp:media-text -->
                </div>
                <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
                <!-- /wp:planet4-block-templates/page-header -->
                ';

		$updated_post_content = $content . $page->post_content;

		gpch_helper_update_meta_options( $page_meta_data, $page_title, $page, true );

		$post_args = array(
			'post_content' => wp_slash( $updated_post_content ),
			'ID'           => $page->ID,
		);

		try {
			wp_update_post( $post_args );
		} catch ( \Throwable $e ) {
			echo 'Error on page ', $page->ID, "\n";
			echo $e->getMessage(), "\n";
		}

		$converted++;
	}

	WP_CLI::success( "Checked " . $i - 1 . " pages." );
}

/**
 * Update the Post meta Options.
 *
 * @param array $page_meta_data To check meta keys.
 * @param object $page That the meta options are gottne from.
 * @param string $page_header_title Page Header title for current Page.
 * @param bool $hide_page_title Check if hide page title option should be enabled.
 */
function gpch_helper_update_meta_options( array $page_meta_data, string $page_header_title, object $page, ?bool $hide_page_title = null ): void {
	$keys_to_update = array(
		'p4_title',
		'p4_subtitle',
		'background_image',
		'background_image_id',
		'p4_description',
		'p4_button_title',
		'p4_button_link',
		'p4_button_link_checkbox',
		'p4_hide_page_title_checkbox',
	);

	// Update specific meta values to empty strings
	foreach ( $keys_to_update as $meta_key ) {
		if ( $meta_key === 'p4_hide_page_title_checkbox' && ! empty( $page_header_title ) ) {
			if ( isset( $page_meta_data[ $meta_key ] ) && in_array( 'on', $page_meta_data[ $meta_key ] ) ) {
				continue;
			}

			//  If Page is Sitemap or Hide Page Title param is not set, then do nothing
			if ( ! isset( $hide_page_title ) ) {
				continue;
			}

			// Update meta value with string 'on'
			update_post_meta( $page->ID, $meta_key, 'on' );
		} else {
			update_post_meta( $page->ID, $meta_key, '' );
		}
	}
}

WP_CLI::add_command( 'gpch headerupdate', 'gpch_convert_page_headers' );


function gpch_trash_pages( $args ) {
	$page_ids = [63505,63515,63517,63518,63595,64052,64103,64120,64585,64592,65009,65048,65493,67327,67329,67330,68895,68896,69169,69170,71102,71103,71106,71108,72483,72642,72643,72822,72891,72919,72920,72925,72926,73087,73098,74415,74521,74522,74570,74819,74843,74871,74880,74886,74887,74927,74945,74986,74992,77900,78078,78122,78149,78150,78151,78401,79145,79146,79492,79493,79537,79590,79611,79701,79712,79851,79881,79885,79985,79998,81687,82092,82098,82222,82339,82838,82839,87147,87258,87296,88316,88404,88581,88692,88736,88740,88807,88815,88875,88877,88973,88974,89227,91993,91994,91997,92122,92123,92125,92128,92129,92130,92131,92530,93078,93128,93135,93138,93139,93324,93609,93804,93805,93806,93807,93955,94054,94321,94431,94432,94758,94759,94760,94888,95077,95134,95409,96020,96366,96408,96409,96524,96554,96580,96784,96795,97044,97046,97292,97435,97667,97712,97731,97827,97836,97840,97846,97859,97860,97888,97896,97944,98009,98012,98023,98024,98054,98074,98085,98247,98249,98281,98340,98365,98880,98894,99061,99062,99100,99101,99129,99130,99368,99369,99903,99916,99917,100009,100469,100534,100669,100701,100753,100754,100755,100804,100960,100982,100983,101062,101173,101265,101284,101294,101295,101329,101540,101698,102022,102090,102108,102172,102192,102200,102221,102323,102325,102326,102345,102510,102513,102552,102558,102676,102713,102722,102758,102768,102772,102774,102776,102778,102923,102924,102942,102943,102951,102952,102962,102963,103004,103009,103035,103119,103126,103148,103149,103379,103474,103483,103484,103572,103617,104433,104679,104680,104681,104782,104883,104884,104906,105263,105855,106508,106865,107085,107086,107183,107829,108190,108291];

	foreach ( $page_ids as $id ) {
		print( "Trashing post with ID: " . $id . "\n" );

		try {
			wp_trash_post( $id );
		}
		catch (Exception $e) {
			print($e->getMessage() . "\n");
		}
	}
}

WP_CLI::add_command( 'gpch pagescleanup', 'gpch_trash_pages' );


