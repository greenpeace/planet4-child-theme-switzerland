<?php

if ( ! function_exists( 'p4_child_theme_gpch_user_roles' ) ) {
	add_action( 'admin_init', 'p4_child_theme_gpch_user_roles' );

	/**
	 * Adds additional user roles and manages capabilities
	 */
	function p4_child_theme_gpch_user_roles() {
		// New role for HR
		add_role( 'human-resources', __( 'Human Resources', 'planet4-child-theme-switzerland' ), array(
			'read'              => true,
			'edit_job'          => true,
			'read_job'          => true,
			'delete_jobs'       => true,
			'edit_jobs'         => true,
			'edit_others_jobs'  => true,
			'publish_jobs'      => true,
			'read_private_jobs' => true,
		) );

		// Add capabilities to editor role
		$editor_role = get_role( 'editor' );

		if ( $editor_role ) {
			// Jobs
			$editor_role->add_cap( 'edit_job', true );
			$editor_role->add_cap( 'read_job', true );
			$editor_role->add_cap( 'delete_jobs', true );
			$editor_role->add_cap( 'edit_jobs', true );
			$editor_role->add_cap( 'edit_others_jobs', true );
			$editor_role->add_cap( 'publish_jobs', true );
			$editor_role->add_cap( 'read_private_jobs', true );

			// Archived Posts
			$editor_role->add_cap( 'edit_archived_post', true );
			$editor_role->add_cap( 'read_archived_post', true );
			$editor_role->add_cap( 'delete_archived_posts', true );
			$editor_role->add_cap( 'edit_archived_posts', true );
			$editor_role->add_cap( 'edit_others_archived_posts', true );
			$editor_role->add_cap( 'publish_archived_posts', true );

			// Magazine Redirects
			$editor_role->add_cap( 'edit_magredirect', true );
			$editor_role->add_cap( 'read_magredirect', true );
			$editor_role->add_cap( 'delete_magredirects', true );
			$editor_role->add_cap( 'edit_magredirects', true );
			$editor_role->add_cap( 'edit_others_magredirects', true );
			$editor_role->add_cap( 'publish_magredirects', true );
			$editor_role->add_cap( 'read_private_magredirects', true );

			// Gravity forms
			// see: https://docs.gravityforms.com/role-management-guide/#gravity-forms-core-capabilities
			$editor_role->add_cap( 'gravityforms_edit_forms', true );
			$editor_role->add_cap( 'gravityforms_delete_forms', true );
			$editor_role->add_cap( 'gravityforms_create_form', true );
			$editor_role->add_cap( 'gravityforms_view_entries', true );
			$editor_role->add_cap( 'gravityforms_edit_entries', true );
			$editor_role->add_cap( 'gravityforms_delete_entries', true );
			$editor_role->add_cap( 'gravityforms_view_settings', false );
			$editor_role->add_cap( 'gravityforms_edit_settings', false );
			$editor_role->add_cap( 'gravityforms_export_entries', true );
			$editor_role->add_cap( 'gravityforms_uninstall', false );
			$editor_role->add_cap( 'gravityforms_view_entry_notes', true );
			$editor_role->add_cap( 'gravityforms_edit_entry_notes', true );
			$editor_role->add_cap( 'gravityforms_view_updates', false );
			$editor_role->add_cap( 'gravityforms_view_addons', false );
			$editor_role->add_cap( 'gravityforms_preview_forms', true );
			$editor_role->add_cap( 'gravityforms_system_status', false );
			$editor_role->add_cap( 'gravityforms_logging', false );
		}

		// Add capabilities to admin role
		$admin_role = get_role( 'administrator' );

		if ( $admin_role ) {
			// Jobs
			$admin_role->add_cap( 'edit_job', true );
			$admin_role->add_cap( 'read_job', true );
			$admin_role->add_cap( 'delete_jobs', true );
			$admin_role->add_cap( 'edit_jobs', true );
			$admin_role->add_cap( 'edit_others_jobs', true );
			$admin_role->add_cap( 'publish_jobs', true );
			$admin_role->add_cap( 'read_private_jobs', true );

			// Archived Posts
			$admin_role->add_cap( 'edit_archived_post', true );
			$admin_role->add_cap( 'read_archived_post', true );
			$admin_role->add_cap( 'delete_archived_posts', true );
			$admin_role->add_cap( 'edit_archived_posts', true );
			$admin_role->add_cap( 'edit_others_archived_posts', true );
			$admin_role->add_cap( 'publish_archived_posts', true );
			$admin_role->add_cap( 'read_private_archived_posts', true );

			// Magazine Redirects
			$admin_role->add_cap( 'edit_magredirect', true );
			$admin_role->add_cap( 'read_magredirect', true );
			$admin_role->add_cap( 'delete_magredirects', true );
			$admin_role->add_cap( 'edit_magredirects', true );
			$admin_role->add_cap( 'edit_others_magredirects', true );
			$admin_role->add_cap( 'publish_magredirects', true );
			$admin_role->add_cap( 'read_private_magredirects', true );
		}
	}
}

/**
 * Redirection Plugin Editor access
 */
add_filter( 'redirection_role', 'gpch_redirection_access_to_editor_role' );

function gpch_redirection_access_to_editor_role() {
	return 'edit_pages';
}
