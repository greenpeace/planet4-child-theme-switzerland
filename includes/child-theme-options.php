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

	add_settings_section(
		'gpch_child_ssa',
		__( 'Server Side Analytics', 'planet4-child-theme-switzerland' ), 'gpch_child_section_ssa_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_ssa_properties',
		__( 'Google Analytics Properties (comma separated)', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_ssa_properties_callback',
		'gpch_child',
		'gpch_child_ssa',
		array(
			'label_for' => 'gpch_child_field_ssa_properties',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_ssa_test_mode',
		__( 'Test mode for Server Side Analytics', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_ssa_test_mode_callback',
		'gpch_child',
		'gpch_child_ssa',
		array(
			'label_for' => 'gpch_child_field_ssa_test_mode',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_section(
		'gpch_child_gf_embed',
		__( 'Embed Gravity Forms', 'planet4-child-theme-switzerland' ), 'gpch_child_section_gf_embed_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_gf_embed_whitelist',
		__( 'Gravity Forms Embed Whitelist', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_gf_embed_whitelist_callback',
		'gpch_child',
		'gpch_child_gf_embed',
		array(
			'label_for' => 'gpch_child_field_gf_embed_whitelist',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_section(
		'gpch_child_twilio',
		__( 'Twilio SMS', 'planet4-child-theme-switzerland' ), 'gpch_child_section_twilio_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_twilio_sid',
		__( 'Twilio Account SID', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_twilio_callback',
		'gpch_child',
		'gpch_child_twilio',
		array(
			'label_for' => 'gpch_child_field_twilio_sid',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_twilio_auth',
		__( 'Twilio Auth Token', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_twilio_callback',
		'gpch_child',
		'gpch_child_twilio',
		array(
			'label_for' => 'gpch_child_field_twilio_auth',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_twilio_number',
		__( 'Twilio sender number with SMS capability', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_twilio_callback',
		'gpch_child',
		'gpch_child_twilio',
		array(
			'label_for' => 'gpch_child_field_twilio_number',
			'class'     => 'gpch_child_row',
		)
	);
}

/**
 * Register our gpch_child_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'gpch_child_settings_init' );


/**
 * Inxmail Section callback function.
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
 * Twilio Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_twilio_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Twilio is used to send SMS.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Inxmail text fields callback function.
 *
 * @param array $args
 */
function gpch_child_fields_twilio_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo ( isset( $options[ $args['label_for'] ] ) ) ? $options[ $args['label_for'] ] : '' ?>">
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
 * SSA Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_ssa_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Server Side Analytics sends events to Google Analytics server side for better data quality.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Analytics properties field callback function.
 *
 * @param array $args
 */
function gpch_child_field_ssa_properties_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[ $args['label_for'] ] ?>">
    <p class="description">
		<?php esc_html_e( 'Comma separated list of Google Analytics tracking ids. For Example: "UA-12345678-1,UA-12345678-2".', 'planet4-child-theme-switzerland' ); ?>
    </p>

	<?php
}


/**
 * Test Mode field callback function.
 *
 * @param array $args
 */
function gpch_child_field_ssa_test_mode_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="1" <?php echo ( array_key_exists( $args['label_for'], $options ) && $options[ $args['label_for'] ] == 1 ) ? ' checked="checked"' : '' ?>>
    <p class="description">
		<?php esc_html_e( 'Use Server Side Analytics in test mode. All events will have "Test: " added to the event category.', 'planet4-child-theme-switzerland' ); ?>
    </p>

	<?php
}

/**
 * Gravity Forms Embed Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_gf_embed_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'A whitelist of domains that are allowed to embed Gravity Forms. One per line, wildcard allowed.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Gravity Forms Embed Whitelist callback function.
 *
 * @param array $args
 */
function gpch_child_field_gf_embed_whitelist_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>"
              name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo ( isset( $options[ $args['label_for'] ] ) ) ? $options[ $args['label_for'] ] : '' ?></textarea>

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
