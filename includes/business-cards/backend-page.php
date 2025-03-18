<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

/**
 * Adds a "Business Card" submenu under the "Users" menu in the WordPress admin.
 *
 * The submenu displays an ACF field group that can be edited and saved.
 *
 * @return void
 */
function gpch_add_business_card_submenu() {
	add_users_page(
		__( 'My Business Card', 'planet4-child-theme-switzerland' ), // Page title
		__( 'My Business Card', 'planet4-child-theme-switzerland' ), // Menu title
		'edit_business_card',                 // Capability required to view this page
		'business-card',                      // Slug for the submenu
		'gpch_render_business_card_page'      // Callback function to display the page
	);
}

add_action( 'admin_menu', 'gpch_add_business_card_submenu' );


/**
 * Renders the "Business Card" submenu page.
 *
 * Displays the ACF field group and handles its rendering and saving logic.
 *
 * @return void
 */
function gpch_render_business_card_page() {
	// Check user capabilities
	if ( ! current_user_can( 'read' ) ) {
		return;
	}

	// Save data
	if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['business_card_nonce'] ) ) {
		gpch_generate_business_card_id_on_save( get_current_user_id() );

		if ( wp_verify_nonce( $_POST['business_card_nonce'], 'save_business_card_fields' ) ) {
			// Save ACF data
			if ( function_exists( 'acf_save_post' ) ) {
				acf_save_post( 'user_' . get_current_user_id() );
				// Show an admin notice after saving
				add_action(
					'admin_notices',
					function () {
						echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( __( 'Business Card updated successfully.', 'planet4-child-theme-switzerland' ) ) . '</p></div>';
					}
				);
			}
		} else {
			// Validation error, show message
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( __( 'Nonce verification failed.', 'planet4-child-theme-switzerland' ) ) . '</p></div>';
				}
			);
		}
	}

	// Enqueue ACF styles
	if ( function_exists( 'acf_enqueue_scripts' ) ) {
		acf_enqueue_scripts(); // Enqueue all ACF scripts and styles
	}

	// Get all ACF fields for the user
	$user_acf_fields = get_fields( 'user_' . get_current_user_id() );

	$bc_link_de = get_site_url() . '/de/business-card/' . $user_acf_fields['business_card_id'];
	$bc_link_fr = get_site_url() . '/fr/business-card/' . $user_acf_fields['business_card_id'];

	$qr_de_svg = gpch_generate_qr_code( $bc_link_de );
	$qr_de_png = gpch_generate_qr_code( $bc_link_de, 'png' );
	$qr_fr_svg = gpch_generate_qr_code( $bc_link_fr );
	$qr_fr_png = gpch_generate_qr_code( $bc_link_fr, 'png' );

	$bc_is_enabled = gpch_get_is_business_card_enabled_by_id( $user_acf_fields['business_card_id'] );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Manage My Business Card', 'planet4-child-theme-switzerland' ); ?></h1>
		<p><?php esc_html_e( 'Your personal digital business card.', 'planet4-child-theme-switzerland' ); ?></p>
		<p><a href="https://www.greenpeace.ch/internal-business-cards-documentation"><?php esc_html_e( 'Visit the documentation for more information.', 'planet4-child-theme-switzerland' ); ?></a></p>
		<hr>
		<?php if ( $bc_is_enabled ) : ?>
			<p><b><?php esc_html_e( 'Congratulations, your Business card is published!', 'planet4-child-theme-switzerland' ); ?></b></p>
			<p><?php esc_html_e( 'Your business card is available in German or French.', 'planet4-child-theme-switzerland' ); ?></p>
			<h2 style="margin-top: 2em;"><?php esc_html_e( 'Links', 'planet4-child-theme-switzerland' ); ?></h2>
			<p><?php esc_html_e( 'German link:', 'planet4-child-theme-switzerland' ); ?> <a href="<?php echo esc_html( $bc_link_de ); ?>"><?php echo esc_html( $bc_link_de ); ?></a><br>
				<?php esc_html_e( 'French link:', 'planet4-child-theme-switzerland' ); ?> <a href="<?php echo esc_html( $bc_link_fr ); ?>"><?php echo esc_html( $bc_link_fr ); ?></a>
			</p>
			<h2 style="margin-top: 2em;"><?php esc_html_e( 'QR codes', 'planet4-child-theme-switzerland' ); ?></h2>
		<div class="bc-qr-codes" style="display:flex;flex-wrap:wrap;gap: 2em;">
			<div class="bc-qr-code" style="">
				<p><b><?php esc_html_e( 'German SVG', 'planet4-child-theme-switzerland' ); ?></b>
					<?php esc_html_e( '(use for printing)', 'planet4-child-theme-switzerland' ); ?><br>
					<a download="business-card-qr-de.svg" href="<?php echo esc_html( $qr_de_svg ); ?>">
						Download
					</a>
				</p>
				<img src="<?php echo esc_html( $qr_de_svg ); ?>" style="max-width:180px;">
			</div>

			<div class="bc-qr-code" style="">
				<p><b><?php esc_html_e( 'German PNG', 'planet4-child-theme-switzerland' ); ?></b>
					<?php esc_html_e( '(use on screens)', 'planet4-child-theme-switzerland' ); ?><br>
					<a download="business-card-qr-de.png" href="<?php echo esc_html( $qr_de_png ); ?>">
						Download
					</a>
				</p>
				<img src="<?php echo esc_html( $qr_de_png ); ?>" style="max-width:180px;">
			</div>

			<div class="bc-qr-code" style="">
				<p><b><?php esc_html_e( 'French SVG', 'planet4-child-theme-switzerland' ); ?></b>
					<?php esc_html_e( '(use for printing)', 'planet4-child-theme-switzerland' ); ?><br>
					<a download="business-card-qr-fr.svg" href="<?php echo esc_html( $qr_fr_svg ); ?>">
						Download
					</a>
				</p>
				<img src="<?php echo esc_html( $qr_fr_svg ); ?>" style="max-width:180px;">
			</div>

			<div class="bc-qr-code" style="">
				<p><b><?php esc_html_e( 'French PNG', 'planet4-child-theme-switzerland' ); ?></b>
					<?php esc_html_e( '(use on screens)', 'planet4-child-theme-switzerland' ); ?><br>
					<a download="business-card-qr-fr.png" href="<?php echo esc_html( $qr_fr_png ); ?>">
						Download
					</a>
				</p>
				<img src="<?php echo esc_html( $qr_fr_png ); ?>" style="max-width:180px;">
			</div>
		</div>
		<?php else : ?>
			<p><?php esc_html_e( 'Your business card is deactivated. Activate it and save to start using it.', 'planet4-child-theme-switzerland' ); ?></p>
		<?php endif; ?>
		<hr>
		<h2 style="margin-top: 2em;"><?php esc_html_e( 'Settings', 'planet4-child-theme-switzerland' ); ?></h2>
		<form method="post" action="">
			<?php
			// Security nonce for saving fields
			wp_nonce_field( 'save_business_card_fields', 'business_card_nonce' );

			// Render the ACF field group
			if ( function_exists( 'acf_form' ) ) {
				acf_form(
					[
						'post_id'      => 'user_' . get_current_user_id(), // Current user
						'field_groups' => [ 'user_business_card' ], // ACF field group
						'return'       => add_query_arg( 'updated', 'true', wp_get_referer() ),
						'submit_value' => __( 'Save Business Card', 'planet4-child-theme-switzerland' ),
						'uploader'     => 'basic',
					]
				);
			}
			?>
		</form>
	</div>
	<?php
}


/**
 * Generates a QR code for a given URL and returns it as a base64-encoded image source.
 *
 * This function uses the `endroid/qr-code` library, which must be installed via Composer.
 *
 * @param string $url The URL for which the QR code should be generated.
 * @param string $format The file format to generate, either 'svg' (default) or 'png'.
 * @return string The base64-encoded image source for the QR code.
 */
function gpch_generate_qr_code( $url, $format = 'svg' ) {
	if ( ! class_exists( \Endroid\QrCode\QrCode::class ) ) {
		return 'Error: QR Code library is not installed. Run "composer require endroid/qr-code".';
	}

	if ( $format === 'png' ) {
		$result = Builder::create()
			->writer( new PngWriter() )
			->writerOptions( [] )
			->data( $url )
			->encoding( new Encoding( 'UTF-8' ) )
			->errorCorrectionLevel( ErrorCorrectionLevel::High )
			->size( 600 )
			->margin( 20 )
			->roundBlockSizeMode( RoundBlockSizeMode::Margin )
			->logoPath( __DIR__ . '/../../images/gp-g-green-white-bg.png' )
			->logoResizeToWidth( 160 )
			->logoPunchoutBackground( false )
			->validateResult( false )
			->build();
	} else {
		$result = Builder::create()
			->writer( new SvgWriter() )
			->writerOptions( [] )
			->data( $url )
			->encoding( new Encoding( 'UTF-8' ) )
			->errorCorrectionLevel( ErrorCorrectionLevel::High )
			->size( 300 )
			->margin( 10 )
			->roundBlockSizeMode( RoundBlockSizeMode::Margin )
			->logoPath( __DIR__ . '/../../images/gp-g-green-white-bg.png' )
			->logoResizeToWidth( 80 )
			->logoPunchoutBackground( false )
			->validateResult( false )
			->build();
	}

	// Output as a data URI
	return $result->getDataUri();
}
