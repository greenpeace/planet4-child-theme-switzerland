wp.domReady( () => {

	// core/heading
	wp.blocks.registerBlockStyle( 'core/heading', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	} );

	wp.blocks.registerBlockStyle( 'core/heading', {
		name: 'underline',
		label: 'Underline',
	} );

	wp.blocks.registerBlockStyle( 'core/heading', {
		name: 'block',
		label: 'Block',
	} );

	// core/list
	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'default',
		label: 'Default',
		isDefault: true,
	} );

	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'accent-1',
		label: 'Colorful 1',
	} );

	wp.blocks.registerBlockStyle( 'core/list', {
		name: 'accent-2',
		label: 'Colorful 2',
	} );

	// core/separator
	wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );
	wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );

	wp.blocks.registerBlockStyle( 'core/separator', {
		name: 'block',
		label: 'Block',
	} );

} );