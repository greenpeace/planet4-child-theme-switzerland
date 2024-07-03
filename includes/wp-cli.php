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
