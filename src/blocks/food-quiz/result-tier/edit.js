import { useBlockProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes }) {
	const { tierIndex = 0 } = attributes;
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Result Tier Settings', 'planet4-child-theme-switzerland')} initialOpen={true}>
					<p>{__('This is Result Tier ' + (tierIndex + 1) + ' content. You can add any blocks here that will be shown when this tier is selected in the quiz results.', 'planet4-child-theme-switzerland')}</p>
				</PanelBody>
			</InspectorControls>
			<div {...useBlockProps({ className: 'food-quiz__tier-editor' })}>
				<InnerBlocks
					allowedBlocks={['core/paragraph', 'core/heading', 'core/image']}
					template={[["core/heading", { content: 'Result Tier ' + (tierIndex + 1), className: 'is-style-no-underline'}],["core/paragraph", { content: 'Lorem ipsum...' }]]}
					templateLock={false}
					renderAppender={InnerBlocks.ButtonBlockAppender}
				/>
			</div>
		</>
	);
}
