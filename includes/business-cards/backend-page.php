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
function gpch_add_business_card_submenu()
{
	add_users_page(
		__('My Business Card', 'textdomain'), // Page title
		__('My Business Card', 'textdomain'), // Menu title
		'edit_business_card',                         // Capability required to view this page
		'business-card',                      // Slug for the submenu
		'gpch_render_business_card_page'      // Callback function to display the page
	);
}

add_action('admin_menu', 'gpch_add_business_card_submenu');


/**
 * Renders the "Business Card" submenu page.
 *
 * Displays the ACF field group and handles its rendering and saving logic.
 *
 * @return void
 */
function gpch_render_business_card_page()
{
	// Check user capabilities
	if (!current_user_can('read')) {
		return;
	}

	// Save data
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['business_card_nonce'])) {
		gpch_generate_business_card_id_on_save(get_current_user_id());

		if (wp_verify_nonce($_POST['business_card_nonce'], 'save_business_card_fields')) {
			// Save ACF data
			if (function_exists('acf_save_post')) {
				acf_save_post('user_' . get_current_user_id());
				// Show an admin notice after saving
				add_action('admin_notices', function () {
					echo '<div class="notice notice-success is-dismissible"><p>' . __('Business Card updated successfully.', 'textdomain') . '</p></div>';
				});
			}
		} else {
			// Validation error, show message
			add_action('admin_notices', function () {
				echo '<div class="notice notice-error is-dismissible"><p>' . __('Nonce verification failed.', 'textdomain') . '</p></div>';
			});
		}
	}

	// Enqueue ACF styles
	if (function_exists('acf_enqueue_scripts')) {
		acf_enqueue_scripts(); // Enqueue all ACF scripts and styles
	}

	// Get all ACF fields for the user
	$user_acf_fields = get_fields('user_' . get_current_user_id());

	$bc_link = get_site_url() . '/business-card/' . $user_acf_fields['business_card_id'];
	?>
	<div class="wrap">
		<h1><?php _e('Manage My Business Card', 'textdomain'); ?></h1>
		<p><?php _e('Your personal business card that will be published on the website.', 'textdomain'); ?></p>
		<hr>
		<?php if (isset($user_acf_fields['enable_business_card'])): ?>
			<h2><?php _e('Congratulations, your Business card is published!', 'textdomain'); ?></h2>
			<p><?php _e('Link to your business card', 'textdomain'); ?>: <a href="<?php echo $bc_link ?>"><?php echo $bc_link ?></a></p>
			<p><?php _e('You can also use a QR code:', 'textdomain'); ?>:<br> <img src="<?php echo generate_qr_code($bc_link) ?>" style="max-width:180px;"></p>
		<?php else: ?>
			<p><?php _e('Your business card is deactivated. Activate it and save to start using it.', 'textdomain'); ?></p>
		<?php endif; ?>
		<hr>
		<h2><?php _e('Settings', 'textdomain'); ?></h2>
		<form method="post" action="">
			<?php
			// Security nonce for saving fields
			wp_nonce_field('save_business_card_fields', 'business_card_nonce');

			// Render the ACF field group
			if (function_exists('acf_form')) {
				acf_form([
					'post_id'      => 'user_' . get_current_user_id(), // Current user
					'field_groups' => ['user_business_card'], // ACF field group
					'return'       => add_query_arg('updated', 'true', wp_get_referer()),
					'submit_value' => __('Save Business Card', 'textdomain'),
					'uploader'     => 'basic',
				]);
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
 * @return string The base64-encoded image source for the QR code.
 */
function generate_qr_code($url)
{
	if (!class_exists(\Endroid\QrCode\QrCode::class)) {
		return 'Error: QR Code library is not installed. Run "composer require endroid/qr-code".';
	}

	$result = Builder::create()
		->writer(new SvgWriter())
		->writerOptions([])
		->data($url)
		->encoding(new Encoding('UTF-8'))
		->errorCorrectionLevel(ErrorCorrectionLevel::High)
		->size(300)
		->margin(10)
		->roundBlockSizeMode(RoundBlockSizeMode::Margin)
		->logoPath(__DIR__.'/../../images/gp-g-green-white-bg.png')
		->logoResizeToWidth(80)
		->logoPunchoutBackground(false)
		->validateResult(false)
		->build();

	// Output as a PNG data URI
	return $result->getDataUri();
}
