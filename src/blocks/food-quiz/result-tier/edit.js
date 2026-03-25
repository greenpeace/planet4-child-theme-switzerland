import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit( { attributes } ) {
	const { tierIndex = 0 } = attributes;
	return (
		<>
			<div { ...useBlockProps( { className: 'food-quiz__tier-editor' } ) }>
				<InnerBlocks
					allowedBlocks={ [
						'planet4-child-theme-switzerland/food-quiz-result-tier-title',
						'core/paragraph',
						'core/heading',
						'core/image',
						'core/separator',
						'planet4-blocks/take-action-boxout',
					] }
					template={ [
						[ 'planet4-child-theme-switzerland/food-quiz-result-tier-title', { lock: { remove: true, move: false } } ],
						[ 'core/heading', { content: 'Result Tier ' + ( tierIndex + 1 ), className: 'is-style-no-underline' } ],
						[ 'core/paragraph', { content: 'Lorem ipsum...' } ],
					] }
					templateLock={ false }
					renderAppender={ InnerBlocks.ButtonBlockAppender }
				/>
			</div>
		</>
	);
}
