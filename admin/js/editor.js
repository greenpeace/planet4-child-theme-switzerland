wp.domReady(() => {

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

	wp.blocks.registerBlockStyle('core/heading', {
		name: 'block',
		label: 'Block',
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

	// core/separator
	wp.blocks.unregisterBlockStyle('core/separator', 'dots');
	wp.blocks.unregisterBlockStyle('core/separator', 'wide');
});