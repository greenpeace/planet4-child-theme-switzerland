<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo esc_html( $form['title'] ); ?></title>
	<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap&subset=latin-ext"/>
	<?php
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		do_action( 'gfiframe_head', $form_id, $form );
	?>
	<?php GFFormDisplay::print_form_scripts( $form, true ); // ajax = true ?>
	<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>
	<link rel='stylesheet' id='gfiframe-style'  href='<?php echo esc_attr( get_site_url() ); ?>/wp-content/themes/planet4-child-theme-switzerland/css/gravity-forms-iframe.css' type='text/css' media='all' />
</head>
<body>
<?php gravity_form( $form_id, $display_title, $display_description, false, null, true ); ?>
<?php wp_footer(); ?>
</body>
</html>
