<?php

/**
 * Custom Options for the GPCH child theme
 */
function gpch_child_settings_init() {
	// Register settings
	register_setting( 'gpch_child', 'gpch_child_options' );

	add_settings_section(
		'gpch_child_inxmail',
		__( 'Inxmail Connector', 'planet4-child-theme-switzerland' ), 'gpch_child_section_inxmail_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_inxmail_user',
		__( 'Inxmail API user', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_inxmail_callback',
		'gpch_child',
		'gpch_child_inxmail',
		array(
			'label_for' => 'gpch_child_field_inxmail_user',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_inxmail_pass',
		__( 'Inxmail API password', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_inxmail_password_callback',
		'gpch_child',
		'gpch_child_inxmail',
		array(
			'label_for' => 'gpch_child_field_inxmail_pass',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_inxmail_url',
		__( 'Inxmail API URL', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_inxmail_callback',
		'gpch_child',
		'gpch_child_inxmail',
		array(
			'label_for' => 'gpch_child_field_inxmail_url',
			'class'     => 'gpch_child_row',
		)
	);
}

/**
 * Register our gpch_child_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'gpch_child_settings_init' );


/**
 * SSA Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_inxmail_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'The Inxmail connector registers newsletter subscriptions in forms directly in Inxmail', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Inxmail text fields callback function.
 *
 * @param array $args
 */
function gpch_child_fields_inxmail_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[ $args['label_for'] ] ?>">
	<?php
}


/**
 * Inxmail text fields callback function.
 *
 * @param array $args
 */
function gpch_child_field_inxmail_password_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="password" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[ $args['label_for'] ] ?>">
	<?php
}


/**
 * Add the menu page as subpage of Wordpress settings.
 */
function gpch_child_options_page() {
	add_submenu_page(
		'options-general.php',
		'GPCH Child',
		'GPCH Options',
		'manage_options',
		'gpch_child',
		'gpch_child_options_page_html'
	);
}


/**
 * Register our gpch_child_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'gpch_child_options_page' );


/**
 * Menu callback function
 */
function gpch_child_options_page_html() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// show error/update messages
	settings_errors( 'gpch_child_messages' );
	?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "gpch_child"
			settings_fields( 'gpch_child' );

			do_settings_sections( 'gpch_child' );

			submit_button( 'Save Settings' );
			?>
        </form>
    </div>
	<?php
}
