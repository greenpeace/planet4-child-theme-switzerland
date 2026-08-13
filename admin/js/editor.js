/* global gpchUserData */

wp.domReady( () => {
	/**
	 * Remove unwanted block default styles and add our own where needed
	 *
	 * @see https://www.billerickson.net/block-styles-in-gutenberg/
	 */

	wp.blocks.unregisterBlockStyle( 'core/heading', 'chevron' );

	wp.blocks.registerBlockStyle( 'core/heading', {
		name: 'no-underline',
		label: 'No Underline',
	} );

	// core/paragraph
	wp.blocks.registerBlockStyle( 'core/paragraph', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	} );

	wp.blocks.registerBlockStyle( 'core/paragraph', {
		name: 'accent-1',
		label: 'Colorful 1 Beige',
	} );

	wp.blocks.registerBlockStyle( 'core/paragraph', {
		name: 'accent-2',
		label: 'Colorful 2 Green',
	} );

	// core/list
	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	} );

	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'accent-info',
		label: 'Info List',
	} );

	// core/quote
	wp.blocks.unregisterBlockStyle( 'core/heading', 'plain' );

	// core/separator
	wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );
	wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );

	// core/column
	wp.blocks.registerBlockStyle( 'core/columns', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	} );

	wp.blocks.registerBlockStyle( 'core/columns', {
		name: 'petition-left',
		label: 'Petition Column Left',
	} );

	// core/table
	wp.blocks.unregisterBlockStyle( 'core/table', 'stripes' );

	// core/column
	wp.blocks.registerBlockStyle( 'core/column', {
		name: 'background-1',
		label: 'Background Beige',
	} );

	// core/group
	wp.blocks.registerBlockStyle( 'core/group', {
		name: 'background-1',
		label: 'Background Beige',
	} );

	wp.blocks.registerBlockStyle( 'core/group', {
		name: 'quote',
		label: 'Quote',
	} );
} );

const { addFilter } = wp.hooks;

// Add a checkbox to the post settings panel to hide the header image
const { CheckboxControl } = wp.components;
const { useDispatch, useSelect } = wp.data;
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { __ } = wp.i18n;

const HeaderImageSetting = () => {
	const meta = useSelect( select => select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {}, [] );
	const { editPost } = useDispatch( 'core/editor' );

	if ( 'post' !== gpchUserData.post_type ) {
		return null;
	}

	return wp.element.createElement(
		PluginDocumentSettingPanel,
		{
			name: 'gpch-header-image-settings',
			title: __( 'General settings', 'planet4-child-theme-switzerland' ),
		},
		wp.element.createElement( CheckboxControl, {
			label: __( 'Hide post header image', 'planet4-child-theme-switzerland' ),
			checked: Boolean( meta.gpch_hide_header_image ),
			onChange: value => {
				editPost( {
					meta: {
						...meta,
						gpch_hide_header_image: value,
					},
				} );
			},
		} )
	);
};

registerPlugin( 'gpch-header-image-settings', { render: HeaderImageSetting } );

// Remove AJAX toggle default introduced by planet4-plugin-gutenberg-block that causes issues
const addGravityFormsBlockFilter = () => {
	const setAJAXToggleDefaultTrue = ( settings, name ) => {
		if ( 'gravityforms/form' !== name ) {
			return settings;
		}

		settings.attributes.ajax.default = false;

		return settings;
	};

	addFilter( 'blocks.registerBlockType', 'planet4-blocks/filters/file', setAJAXToggleDefaultTrue );
};

addGravityFormsBlockFilter();
