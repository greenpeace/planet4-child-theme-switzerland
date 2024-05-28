<?php

/**
 * Custom Options for the GPCH child theme
 */
function gpch_child_settings_init() {
	// Register settings
	register_setting( 'gpch_child', 'gpch_child_options' );


	add_settings_section(
		'gpch_child_ssa',
		__( 'Web Analytics', 'planet4-child-theme-switzerland' ), 'gpch_child_section_ssa_callback',
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
		'gpch_child_field_gp_user_id_salt',
		__( 'Salt for gp_user_id', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_gp_user_id_salt_callback',
		'gpch_child',
		'gpch_child_ssa',
		array(
			'label_for' => 'gpch_child_field_gp_user_id_salt',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_section(
		'gpch_child_content_embed',
		__( 'Allowlist for websites to embed our content', 'planet4-child-theme-switzerland' ), 'gpch_child_section_content_embed_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_content_embed_allowlist',
		__( 'List of website domains that are allowed to embed ANY of our content.', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_content_embed_allowlist_callback',
		'gpch_child',
		'gpch_child_content_embed',
		array(
			'label_for' => 'gpch_child_field_content_embed_allowlist',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_section(
		'gpch_child_gf_embed',
		__( 'Allowlist websites that can embed our Gravity Forms', 'planet4-child-theme-switzerland' ), 'gpch_child_section_gf_embed_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_gf_embed_whitelist',
		__( 'List of websites that are allowed to embed our Gravity Forms.', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_gf_embed_whitelist_callback',
		'gpch_child',
		'gpch_child_gf_embed',
		array(
			'label_for' => 'gpch_child_field_gf_embed_whitelist',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_section(
		'gpch_child_bitly',
		__( 'Bitly API', 'planet4-child-theme-switzerland' ), 'gpch_child_section_bitly_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_bitly_token',
		__( 'Bitly API token', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_bitly_callback',
		'gpch_child',
		'gpch_child_bitly',
		array(
			'label_for' => 'gpch_child_field_bitly_token',
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

	add_settings_section(
		'gpch_child_raisenow',
		__( 'RaiseNow API', 'planet4-child-theme-switzerland' ), 'gpch_child_section_raisenow_callback',
		'gpch_child'
	);

	add_settings_field(
		'gpch_child_field_raisenow_user',
		__( 'Raisenow API user', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_raisenow_callback',
		'gpch_child',
		'gpch_child_raisenow',
		array(
			'label_for' => 'gpch_child_field_raisenow_user',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_raisenow_pass',
		__( 'Raisenow API password', 'planet4-child-theme-switzerland' ),
		'gpch_child_field_password_callback',
		'gpch_child',
		'gpch_child_raisenow',
		array(
			'label_for' => 'gpch_child_field_raisenow_pass',
			'class'     => 'gpch_child_row',
		)
	);

	add_settings_field(
		'gpch_child_field_raisenow_url',
		__( 'Raisenow API URL', 'planet4-child-theme-switzerland' ),
		'gpch_child_fields_raisenow_callback',
		'gpch_child',
		'gpch_child_raisenow',
		array(
			'label_for' => 'gpch_child_field_raisenow_url',
			'class'     => 'gpch_child_row',
		)
	);
}

/**
 * Register our gpch_child_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'gpch_child_settings_init' );


/**
 * Bitly Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_bitly_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Bitly is used to shorten links.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Bitly text fields callback function.
 *
 * @param array $args
 */
function gpch_child_fields_bitly_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo ( isset( $options[ $args['label_for'] ] ) ) ? $options[ $args['label_for'] ] : '' ?>">
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
 * Twilio text fields callback function.
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
 * SSA Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_ssa_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Web analytics settings', 'planet4-child-theme-switzerland' ); ?></p>
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
 * gp_user_id Salt field callback function.
 *
 * @param array $args
 */
function gpch_child_field_gp_user_id_salt_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[ $args['label_for'] ] ?>">
    <p class="description">
		<?php esc_html_e( 'Salt value to generate the gp_user_id for Mixpanel (do not change!)', 'planet4-child-theme-switzerland' ); ?>
    </p>
	<?php
}

/**
 * Gravity Forms Embed Section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_content_embed_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'A whitelist of domains that are allowed to embed any of our content. One per line, wildcard allowed.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * Gravity Forms Embed Whitelist callback function.
 *
 * @param array $args
 */
function gpch_child_field_content_embed_allowlist_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>"
              name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo ( isset( $options[ $args['label_for'] ] ) ) ? $options[ $args['label_for'] ] : '' ?></textarea>

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
 * RaiseNow section callback function.
 *
 * @param array $args The settings array, defining title, id, callback.
 */
function gpch_child_section_raisenow_callback( $args ) {
	?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'The RaiseNow API is used in the blocks plugin, for example for the donations progress bar. A read-only user is recommended.', 'planet4-child-theme-switzerland' ); ?></p>
	<?php
}

/**
 * RaiseNow text fields callback function.
 *
 * @param array $args
 */
function gpch_child_fields_raisenow_callback( $args ) {
	$options = get_option( 'gpch_child_options' );
	?>
    <input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>"
           name="gpch_child_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
           value="<?php echo $options[ $args['label_for'] ] ?>">
	<?php
}

/**
 * Password text fields callback function.
 *
 * @param array $args
 */
function gpch_child_field_password_callback( $args ) {
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
