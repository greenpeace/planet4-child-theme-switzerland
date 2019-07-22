<?php

if ( ! function_exists( 'p4_child_theme_gpch_user_roles' ) ) {
	add_action( 'admin_init', 'p4_child_theme_gpch_user_roles' );

	/**
	 * Adds additional user roles and manages capabilities
	 */
	function p4_child_theme_gpch_user_roles() {
		// New role for HR
		// Add the role with basic capabilities
		// Roles are persistent, which means the role will only be added if it doesn't exist yet.
		add_role( 'human-resources', __( 'Human Resources', 'planet4-child-theme-switzerland' ), array(
			// General
			'read'              => true,

			// Media upload
			'upload_files'      => true,
		) );


		// Add capabilities to hr role
		// These capabilities are added even when the role already exists
		// Be aware that capabilities are persistent. Removing a line below won't revoke an already granted permission.
		// To revoke a capability, change the lines below to use remove_cap() instead of add_cap().
		$hr_role = get_role( 'human-resources' );

		if ( $hr_role ) {
			// General
			$hr_role->add_cap( 'read', true );
			$hr_role->add_cap( 'upload_files', true );
			
			// Jobs
			$hr_role->add_cap( 'edit_job', true );
			$hr_role->add_cap( 'read_job', true );
			$hr_role->add_cap( 'delete_jobs', true );
			$hr_role->add_cap( 'edit_jobs', true );
			$hr_role->add_cap( 'edit_others_jobs', true );
			$hr_role->add_cap( 'publish_jobs', true );
			$hr_role->add_cap( 'read_private_jobs', true );
		}

		// New role for Events
		// Add the role with basic capabilities
		// Roles are persistent, which means the role will only be added if it doesn't exist yet.
		add_role( 'gpch-events', __( 'GPCH Events', 'planet4-child-theme-switzerland' ), array(
			// General
			'read'              => true,

			// Media upload
			'upload_files'      => true,
		) );


		// Add capabilities to events role
		// These capabilities are added even when the role already exists
		// Be aware that capabilities are persistent. Removing a line below won't revoke an already granted permission.
		// To revoke a capability, change the lines below to use remove_cap() instead of add_cap().
		$event_role = get_role( 'gpch-events' );

		if ( $event_role ) {
			// General
			$event_role->add_cap( 'read', true );
			$event_role->add_cap( 'upload_files', true );
			$event_role->add_cap( 'assign_terms', true );
			$event_role->add_cap( 'edit_posts', true ); // Seems to be needed for the tags menu to show, doesn't allow publishing of posts

			// Events
			$event_role->add_cap( 'edit_gpch_event', true );
			$event_role->add_cap( 'read_gpch_event', true );
			$event_role->add_cap( 'delete_gpch_events', true );
			$event_role->add_cap( 'edit_gpch_events', true );
			$event_role->add_cap( 'edit_others_gpch_events', true );
			$event_role->add_cap( 'publish_gpch_events', true );
			$event_role->add_cap( 'read_private_gpch_events', true );
		}


		// Add capabilities to editor role
		// Be aware that capabilities are persistent. Removing a line below won't revoke an already granted permission.
		// To revoke a capability, change the lines below to use remove_cap() instead of add_cap().
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

			// Events
			$editor_role->add_cap( 'edit_gpch_event', true );
			$editor_role->add_cap( 'read_gpch_event', true );
			$editor_role->add_cap( 'delete_gpch_events', true );
			$editor_role->add_cap( 'edit_gpch_events', true );
			$editor_role->add_cap( 'edit_others_gpch_events', true );
			$editor_role->add_cap( 'publish_gpch_events', true );
			$editor_role->add_cap( 'read_private_gpch_events', true );

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
		// Be aware that capabilities are persistent. Removing a line below won't revoke an already granted permission.
		// To revoke a capability, change the lines below to use remove_cap() instead of add_cap().
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

			// Events
			$admin_role->add_cap( 'edit_gpch_event', true );
			$admin_role->add_cap( 'read_gpch_event', true );
			$admin_role->add_cap( 'delete_gpch_events', true );
			$admin_role->add_cap( 'edit_gpch_events', true );
			$admin_role->add_cap( 'edit_others_gpch_events', true );
			$admin_role->add_cap( 'publish_gpch_events', true );
			$admin_role->add_cap( 'read_private_gpch_events', true );

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
