import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { tierIndex = 0 } = attributes;
	return (
		<div { ...useBlockProps.save() } className="food-quiz__tier" data-tier={ tierIndex }>
			<InnerBlocks.Content />
		</div>
	);
}
