import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { breakfastMeals = [], lunchMeals = [], dinnerMeals = [], drinks = [], tierLabels = [], tierThresholds = [] } = attributes;
	const wrapperProps = useBlockProps.save();
	return (
		<div
			{...wrapperProps}
			className={(wrapperProps.className || '') + ' food-quiz'}
			data-breakfast-meals={JSON.stringify(breakfastMeals)}
			data-lunch-meals={JSON.stringify(lunchMeals)}
			data-dinner-meals={JSON.stringify(dinnerMeals)}
			data-drinks={JSON.stringify(drinks)}
			data-tier-thresholds={JSON.stringify(tierThresholds)}
			data-tier-labels={JSON.stringify(tierLabels)}
		>
			<div className="food-quiz__meal-times">
				<div className="food-quiz__meal" data-time="breakfast" data-title={__('Breakfast', 'planet4-child-theme-switzerland')} aria-label="Breakfast"></div>
				<div className="food-quiz__meal" data-time="lunch" data-title={__('Lunch', 'planet4-child-theme-switzerland')} aria-label="Lunch"></div>
				<div className="food-quiz__meal" data-time="dinner" data-title={__('Dinner', 'planet4-child-theme-switzerland')} aria-label="Dinner"></div>
			</div>
			<div className="food-quiz__drinks" data-title={__('Drinks', 'planet4-child-theme-switzerland')}></div>
			<div className="food-quiz__result">
				<div class="wp-block-group read-more-nav is-layout-flow wp-block-group-is-layout-flow">
					<button type="button" class="btn btn-primary food-quiz__calculate" aria-label="{__('Calculate', 'planet4-child-theme-switzerland')}">
						{__('Calculate', 'planet4-child-theme-switzerland')}
					</button>
				</div>
				<div className="food-quiz__result-output">
					<div class="error-message">
						{__('Please select at least one of each meal.', 'planet4-child-theme-switzerland')}
					</div>
					<div class="result-scale"></div>
					<InnerBlocks.Content />
				</div>
			</div>
		</div>
	);
}
