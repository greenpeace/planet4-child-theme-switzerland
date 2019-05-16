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

	// core/column
	// The following would be an ideal solution, but as of 2019-05 it crashed the Gutenberg editor
	// Until this is fixable, the class name "vertically-centered" has to be added manually to the columns element in the advanced section of the editor
	// See https://tickets.greenpeace.ch/view.php?id=128#c89
	/*
	wp.blocks.registerBlockStyle('core/columns', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	});

	wp.blocks.registerBlockStyle('core/columns', {
		name: 'vertically-centered',
		label: 'Center Vertically',
	});
	 */
});