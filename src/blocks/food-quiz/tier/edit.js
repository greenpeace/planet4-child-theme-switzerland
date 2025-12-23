import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes }) {
	const { tierIndex = 0 } = attributes;
	return (
		<div {...useBlockProps({ className: 'food-quiz__tier-editor' })}>
			<h5>{__(`Tier ${tierIndex + 1}`, 'planet4-child-theme-switzerland')}</h5>
			<InnerBlocks
				allowedBlocks={['core/paragraph', 'core/heading', 'core/image']}
				template={[["core/paragraph", { content: 'Lorem ipsum...' }]]}
				templateLock={false}
				renderAppender={InnerBlocks.ButtonBlockAppender}
			/>
		</div>
	);
}
