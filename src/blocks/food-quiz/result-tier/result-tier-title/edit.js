import { useBlockProps } from '@wordpress/block-editor';

export default function Edit( { context } ) {
	const tierIndex = Number.isInteger( context?.[ 'planet4-child-theme-switzerland/food-quiz-tier-index' ] )
		? context[ 'planet4-child-theme-switzerland/food-quiz-tier-index' ]
		: 0;
	const tierLabels = Array.isArray( context?.[ 'planet4-child-theme-switzerland/food-quiz-tier-labels' ] )
		? context[ 'planet4-child-theme-switzerland/food-quiz-tier-labels' ]
		: [];
	const label = tierLabels[ tierIndex ] || '';

	return (
		<div
			{ ...useBlockProps( {
				className: `food-quiz__tier-title result-scale__tier--${ tierIndex }`,
			} ) }
		>
			<p className="food-quiz__tier-title-label">{ label }</p>
		</div>
	);
}
