wp.domReady(() => {
	// Allow styles in columns block without crashing the editor
	// The crash happens because the preview in the style menu doesn't work if the block uses inner blocks
	// https://github.com/WordPress/gutenberg/issues/9897#issuecomment-478362380
	var el = wp.element.createElement;
	var allowColumnStyle = wp.compose.createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			var content = el(BlockEdit, props);

			if (props.name === 'core/columns' && typeof props.insertBlocksAfter === 'undefined') {
				content = el('div', {});
			}

			return el(
				wp.element.Fragment, {}, content
			);
		};
	}, 'allowColumnStyle');

	wp.hooks.addFilter('editor.BlockEdit', 'my/gutenberg', allowColumnStyle);


	/**
	 * Remove unwanted block default styles and add our own where needed
	 *
	 * @see https://www.billerickson.net/block-styles-in-gutenberg/
	 */

	wp.blocks.unregisterBlockStyle('core/heading', 'chevron');

	wp.blocks.registerBlockStyle('core/heading', {
		name: 'no-underline',
		label: 'No Underline',
	});

	// core/paragraph
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'accent-1',
		label: 'Colorful 1 Beige',
	});

	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'accent-2',
		label: 'Colorful 2 Green',
	});

	// core/list
	wp.blocks.registerBlockStyle('core/list', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/list', {
		name: 'accent-info',
		label: 'Info List',
	});

	// core/quote
	wp.blocks.unregisterBlockStyle('core/heading', 'plain');

	// core/separator
	wp.blocks.unregisterBlockStyle('core/separator', 'dots');
	wp.blocks.unregisterBlockStyle('core/separator', 'wide');

	// core/column
	wp.blocks.registerBlockStyle('core/columns', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/columns', {
		name: 'petition-left',
		label: 'Petition Column Left',
	});
	
	// core/table
	wp.blocks.unregisterBlockStyle('core/table', 'stripes');
	
	

	/*
	* Remove the default tags menu where needed.
	*
	* Planet4 replaces the default sidebar element in the post edit screen (for
	* everyone except admins) with a custom element that doesn't allow creating
	* new tags.
	* We've added the same menu to our custom post types, so we need to hide the
	* default element as well.
	*
	* @see: https://github.com/greenpeace/planet4-master-theme/blob/f02ceaf22a0fc455180395c4414efc19e6f36933/classes/class-p4-master-site.php#L742-L750
	* @see: https://github.com/greenpeace/planet4-child-theme-switzerland/blob/8900f2faaf924fde32482be06c0c96b238ce2095/includes/gutenberg-sidebar.php#L75-L91
	*/
	if (gpchUserData.post_type === "gpch_event" || gpchUserData.post_type === "gpch_magredirect" || gpchUserData.post_type === "gpch_archived_post") { // all users, ceratin post types
		wp.data.dispatch( 'core/edit-post').removeEditorPanel( 'taxonomy-panel-post_tag' );
	}
});

const { addFilter } = wp.hooks;

// Remove AJAX toggle default introduced by planet4-plugin-gutenberg-block that causes issues
const addGravityFormsBlockFilter = () => {
	const setAJAXToggleDefaultTrue = (settings, name) => {
		if ('gravityforms/form' !== name) {
			return settings;
		}

		settings.attributes['ajax']['default'] = false;

		return settings;
	};

	addFilter('blocks.registerBlockType', 'planet4-blocks/filters/file', setAJAXToggleDefaultTrue);
};

addGravityFormsBlockFilter();