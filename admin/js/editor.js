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


	// core/heading
	wp.blocks.registerBlockStyle('core/heading', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/heading', {
		name: 'underline',
		label: 'Underline',
	});

	// core/paragraph
	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'accent-1',
		label: 'Colorful 1',
	});

	wp.blocks.registerBlockStyle('core/paragraph', {
		name: 'accent-2',
		label: 'Colorful 2',
	});

	// core/list
	wp.blocks.registerBlockStyle('core/list', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/list', {
		name: 'accent-links',
		label: 'Navigation List',
	});

	wp.blocks.registerBlockStyle('core/list', {
		name: 'accent-info',
		label: 'Info List',
	});

	// core/button
	wp.blocks.unregisterBlockStyle('core/button', 'outline');
	wp.blocks.unregisterBlockStyle('core/button', 'squared');

	wp.blocks.registerBlockStyle('core/button', {
		name: 'full-width',
		label: 'Full Width',
		isDefault: false,
	});
	
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
		name: 'vertically-centered',
		label: 'Center Vertically',
	});

	wp.blocks.registerBlockStyle('core/columns', {
		name: 'petition-left',
		label: 'Petition Column Left',
	});
});