import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { breakfastMeals = [], lunchMeals = [], dinnerMeals = [], drinks = [], tierLabels = [] } = attributes;
	const wrapperProps = useBlockProps.save();
	return (
		<div
			{...wrapperProps}
			className={(wrapperProps.className || '') + ' food-quiz'}
			data-breakfast-meals={JSON.stringify(breakfastMeals)}
			data-lunch-meals={JSON.stringify(lunchMeals)}
			data-dinner-meals={JSON.stringify(dinnerMeals)}
			data-drinks={JSON.stringify(drinks)}
		>
			<div className="food-quiz__inputs">
				<div className="food-quiz__meal-times">
					<div className="food-quiz__meal" data-time="breakfast" data-title={ __('Breakfast', 'planet4-child-theme-switzerland')} aria-label="Breakfast"></div>
					<div className="food-quiz__meal" data-time="lunch" data-title={ __('Lunch', 'planet4-child-theme-switzerland')} aria-label="Lunch"></div>
					<div className="food-quiz__meal" data-time="dinner" data-title={ __('Dinner', 'planet4-child-theme-switzerland')} aria-label="Dinner"></div>
				</div>
				<div className="food-quiz__drinks"></div>
				<div className="food-quiz__result">
					<button type="button" className="food-quiz__calculate">
						Calculate
					</button>
					<div className="food-quiz__result-output">
						<InnerBlocks.Content />
					</div>
				</div>
			</div>
		</div>
	);
}
