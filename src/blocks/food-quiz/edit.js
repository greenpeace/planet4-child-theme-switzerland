import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, InnerBlocks, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, RangeControl, Button, Notice, PanelRow } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';
import ConfirmModal from '../../components/ConfirmModal';
import { chevronUp, chevronDown, trash } from '@wordpress/icons';

import './style.scss';
import './editor.scss';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { breakfastMeals = [], lunchMeals = [], dinnerMeals = [], drinks = [], tierLabels = [], tierThresholds = [] } = attributes;

	function updateMealSet( setName, index, key, value ) {
		/* eslint-disable no-nested-ternary */
		const set = setName === 'breakfast' ? breakfastMeals : setName === 'lunch' ? lunchMeals : dinnerMeals;
		const next = set.map( ( m, i ) => ( i === index ? { ...m, [ key ]: value } : m ) );
		setAttributes( { [ `${ setName }Meals` ]: next } );
	}

	function addMealToSet( setName ) {
		const set = setName === 'breakfast' ? breakfastMeals : setName === 'lunch' ? lunchMeals : dinnerMeals;
		if ( set.length >= 4 ) {
			return;
		}
		setAttributes( {
			[ `${ setName }Meals` ]: [ ...set, { title: 'New meal', score: 1, imageUrl: '' } ],
		} );
	}

	function removeMealFromSet( setName, index ) {
		const set = setName === 'breakfast' ? breakfastMeals : setName === 'lunch' ? lunchMeals : dinnerMeals;
		setAttributes( {
			[ `${ setName }Meals` ]: set.filter( ( _, i ) => i !== index ),
		} );
	}

	function moveMealInSet( setName, index, dir ) {
		const set = setName === 'breakfast' ? breakfastMeals : setName === 'lunch' ? lunchMeals : dinnerMeals;
		const next = set.slice();
		const swap = index + dir;
		if ( swap < 0 || swap >= next.length ) {
			return;
		}
		[ next[ index ], next[ swap ] ] = [ next[ swap ], next[ index ] ];
		setAttributes( { [ `${ setName }Meals` ]: next } );
	}

	function updateDrink( index, key, value ) {
		const next = drinks.map( ( d, i ) => ( i === index ? { ...d, [ key ]: value } : d ) );
		setAttributes( { drinks: next } );
	}

	function addDrink() {
		if ( drinks.length >= 8 ) {
			return;
		}
		setAttributes( {
			drinks: [ ...drinks, { title: 'New drink', score: 0 } ],
		} );
	}

	function removeDrink( index ) {
		setAttributes( { drinks: drinks.filter( ( _, i ) => i !== index ) } );
	}

	function moveDrink( index, dir ) {
		const next = drinks.slice();
		const swap = index + dir;
		if ( swap < 0 || swap >= next.length ) {
			return;
		}
		[ next[ index ], next[ swap ] ] = [ next[ swap ], next[ index ] ];
		setAttributes( { drinks: next } );
	}

	// Confirmation for removals
	const [ confirm, setConfirm ] = useState( { open: false, kind: null, setName: null, index: null } );

	// Which tier tab is active in the editor
	const [ activeTier, setActiveTier ] = useState( 0 );

	// Get the inner blocks of this block so we can select them programmatically
	const innerBlocks = useSelect(
		select => {
			if ( ! clientId ) {
				return [];
			}
			return select( 'core/block-editor' ).getBlocks( clientId ) || [];
		},
		[ clientId ]
	);

	const { selectBlock } = useDispatch( 'core/block-editor' );

	function confirmAndRemoveMeal( setName, index ) {
		setConfirm( { open: true, kind: 'meal', setName, index } );
	}

	function confirmAndRemoveDrink( index ) {
		setConfirm( { open: true, kind: 'drink', setName: null, index } );
	}

	function handleConfirmRemove() {
		if ( confirm.kind === 'meal' && confirm.setName !== null ) {
			removeMealFromSet( confirm.setName, confirm.index );
		} else if ( confirm.kind === 'drink' ) {
			removeDrink( confirm.index );
		}
		setConfirm( { open: false, kind: null, setName: null, index: null } );
	}

	function closeConfirm() {
		setConfirm( { open: false, kind: null, setName: null, index: null } );
	}

	return (
		<>
			<InspectorControls>
				{ ( breakfastMeals.length <= 1 || lunchMeals.length <= 1 || dinnerMeals.length <= 1 ) && (
					<Notice status="warning">
						{ __( 'Add at least 2 options for each meal time to make the quiz meaningful.', 'planet4-child-theme-switzerland' ) }
					</Notice>
				) }
				{ [
					[ 'breakfast', breakfastMeals ],
					[ 'lunch', lunchMeals ],
					[ 'dinner', dinnerMeals ],
				].map( ( [ setName, set ] ) => (
					<PanelBody key={ setName } title={ setName.charAt( 0 ).toUpperCase() + setName.slice( 1 ) + ' meals' } initialOpen={ false }>
						<div className="food-quiz__meal-set">
							{ set.map( ( meal, i ) => (
								<div key={ i } className="food-quiz__editor-item">
									<h3>
										{ __( 'Meal', 'planet4-child-theme-switzerland' ) } #{ i + 1 }
									</h3>
									<TextControl
										label={ __( 'Title', 'planet4-child-theme-switzerland' ) }
										value={ meal.title }
										onChange={ v => updateMealSet( setName, i, 'title', v ) }
									/>
									<RangeControl
										label={ __( 'Grams of CO2 (per serving)', 'planet4-child-theme-switzerland' ) }
										min={ 0 }
										max={ 5000 }
										value={ meal.score }
										onChange={ v => updateMealSet( setName, i, 'score', v ) }
									/>
									<PanelRow>
										<MediaUploadCheck>
											<MediaUpload
												onSelect={ media => updateMealSet( setName, i, 'imageUrl', media.url ) }
												render={ ( { open } ) => (
													<Button onClick={ open } variant="secondary">
														{ meal.imageUrl ? (
															<img
																src={ meal.imageUrl }
																alt=""
																style={ {
																	width: 60,
																	height: 60,
																	objectFit: 'cover',
																} }
															/>
														) : (
															'Choose image'
														) }
													</Button>
												) }
											/>
										</MediaUploadCheck>
										<div className="food-quiz__editor-controls">
											<Button
												icon={ chevronUp }
												onClick={ () => moveMealInSet( setName, i, -1 ) }
												disabled={ i === 0 }
												label={ __( 'Move up', 'planet4-child-theme-switzerland' ) }
												showTooltip={ true }
											/>
											<Button
												icon={ chevronDown }
												onClick={ () => moveMealInSet( setName, i, 1 ) }
												disabled={ i === set.length - 1 }
												label={ __( 'Move down', 'planet4-child-theme-switzerland' ) }
												showTooltip={ true }
											/>
											<Button
												icon={ trash }
												onClick={ () => confirmAndRemoveMeal( setName, i ) }
												isDestructive={ true }
												label={ __( 'Remove', 'planet4-child-theme-switzerland' ) }
												showTooltip={ true }
											/>
										</div>
									</PanelRow>
								</div>
							) ) }
							<div style={ { marginTop: 10 } }>
								<Button variant="primary" onClick={ () => addMealToSet( setName ) } disabled={ set.length >= 8 }>
									{ __( 'Add meal', 'planet4-child-theme-switzerland' ) }
								</Button>
							</div>
						</div>
					</PanelBody>
				) ) }
				<PanelBody title={ __( 'Drinks', 'planet4-child-theme-switzerland' ) } initialOpen={ false }>
					{ drinks.map( ( drink, i ) => (
						<div key={ i } className="food-quiz__editor-item">
							<h3>
								{ __( 'Drink', 'planet4-child-theme-switzerland' ) } #{ i + 1 }
							</h3>
							<TextControl
								label={ __( 'Title', 'planet4-child-theme-switzerland' ) }
								value={ drink.title }
								onChange={ v => updateDrink( i, 'title', v ) }
							/>
							<RangeControl
								label={ __( 'Grams of CO2 (per serving)', 'planet4-child-theme-switzerland' ) }
								min={ 0 }
								max={ 1000 }
								value={ drink.score }
								onChange={ v => updateDrink( i, 'score', v ) }
							/>
							<PanelRow>
								<div className="food-quiz__editor-controls">
									<Button
										icon={ chevronUp }
										onClick={ () => moveDrink( i, -1 ) }
										disabled={ i === 0 }
										label={ __( 'Move up', 'planet4-child-theme-switzerland' ) }
										showTooltip={ true }
									/>
									<Button
										icon={ chevronDown }
										onClick={ () => moveDrink( i, 1 ) }
										disabled={ i === drinks.length - 1 }
										label={ __( 'Move down', 'planet4-child-theme-switzerland' ) }
										showTooltip={ true }
									/>
									<Button
										icon={ trash }
										onClick={ () => confirmAndRemoveDrink( i ) }
										isDestructive={ true }
										label={ __( 'Remove', 'planet4-child-theme-switzerland' ) }
										showTooltip={ true }
									/>
								</div>
							</PanelRow>
						</div>
					) ) }
					<div style={ { marginTop: 10 } }>
						<Button variant="primary" onClick={ addDrink } disabled={ drinks.length >= 10 }>
							{ __( 'Add drink', 'planet4-child-theme-switzerland' ) }
						</Button>
					</div>
				</PanelBody>
				<PanelBody title={ __( 'Result Tier Settings', 'planet4-child-theme-switzerland' ) } initialOpen={ false }>
					{ Array.from( { length: Math.max( tierLabels.length, 5 ) } ).map( ( _, i ) => (
						<div key={ i } className="food-quiz__tier-config">
							<h3>
								{ __( 'Tier', 'planet4-child-theme-switzerland' ) } #{ i + 1 }
							</h3>
							<TextControl
								label={ `Label` }
								value={ tierLabels[ i ] || '' }
								onChange={ v => {
									const len = Math.max( 5, tierLabels.length || 0 );
									/* eslint-disable-next-line no-shadow */
									const next = Array.from( { length: len } ).map( ( _, idx ) => ( idx === i ? v : tierLabels[ idx ] ?? '' ) );
									setAttributes( { tierLabels: next } );
								} }
							/>
							{ i < 4 ? (
								<RangeControl
									label={ __( 'Upper threshold (show this tier when grams of CO2 ≤)', 'planet4-child-theme-switzerland' ) }
									min={ 1 }
									max={ 10000 }
									value={ typeof tierThresholds[ i ] === 'number' ? tierThresholds[ i ] : 0 }
									onChange={ v => {
										const len = Math.max( 5, tierThresholds.length || 0 );
										/* eslint-disable-next-line no-shadow */
										const next = Array.from( { length: len } ).map( ( _, idx ) => ( idx === i ? v : tierThresholds[ idx ] ?? null ) );
										setAttributes( { tierThresholds: next } );
									} }
								/>
							) : (
								<PanelRow>
									<span>
										<strong>{ __( 'Upper threshold: Infinite', 'planet4-child-theme-switzerland' ) }</strong>
									</span>
								</PanelRow>
							) }
						</div>
					) ) }
				</PanelBody>
			</InspectorControls>

			<ConfirmModal
				open={ confirm.open }
				title={ __( 'Confirm removal', 'planet4-child-theme-switzerland' ) }
				message={
					confirm.kind === 'drink'
						? __( 'Are you sure you want to remove this drink?', 'planet4-child-theme-switzerland' )
						: __( 'Are you sure you want to remove this meal?', 'planet4-child-theme-switzerland' )
				}
				onClose={ closeConfirm }
				onConfirm={ handleConfirmRemove }
			/>

			<div { ...useBlockProps() } className="food-quiz food-quiz__editor">
				<div className="food-quiz__editor-preview">
					<div className="food-quiz__meal-times">
						{ [ 'Breakfast', 'Lunch', 'Dinner' ].map( time => (
							<div key={ time } className="food-quiz__meal">
								<h3>{ time }</h3>
								<div className="food-quiz__meal-options">
									{ ( time === 'Breakfast' ? breakfastMeals : time === 'Lunch' ? lunchMeals : dinnerMeals ).slice( 0, 4 ).map( ( m, i ) => (
										<label key={ i } className="fq-option" htmlFor="{`preview-${ time }`}">
											<input type="radio" name={ `preview-${ time }` } />
											<p className="fq-option-title">{ m.title }</p>
											<div className="fq-option-img">{ m.imageUrl ? <img src={ m.imageUrl } alt="" /> : null }</div>
										</label>
									) ) }
								</div>
							</div>
						) ) }
					</div>
					<div className="food-quiz__drinks">
						<h3>{ __( 'Drinks', 'planet4-child-theme-switzerland' ) }</h3>
						<div className="food-quiz__drink-options">
							{ drinks.map( ( d, i ) => (
								<div key={ i } className="fq-drink-wrapper">
									<p className="fq-option-title">{ d.title }</p>
									<div className="fq-drink-controls">
										<button type="button" className="fq-drink-decrease" aria-label="Decrease">
											−
										</button>
										<input type="number" min="0" max="5" value="0" data-index="0" className="fq-drink-input" readOnly />
										<button type="button" className="fq-drink-increase" aria-label="Increase">
											+
										</button>
									</div>
								</div>
							) ) }
						</div>
					</div>
					<hr />
					<div className="food-quiz__tiers-editor">
						<h3>{ __( 'Result descriptions (edit each tier below)', 'planet4-child-theme-switzerland' ) }</h3>
						<div className="food-quiz__tiers-tabs">
							{ Array.from( { length: Math.max( tierLabels.length, 5 ) } ).map( ( _, i ) => (
								<button
									type="button"
									key={ i }
									className={ `fq-tab ${ i === activeTier ? 'is-active' : '' }` }
									onClick={ () => {
										setActiveTier( i );
										if ( innerBlocks && innerBlocks[ i ] ) {
											selectBlock( innerBlocks[ i ].clientId );
										}
									} }
								>
									{ tierLabels[ i ] || `ResultTier ${ i + 1 }` }{ ' ' }
									<small>(≤{ tierThresholds[ i ] !== null && tierThresholds[ i ] !== undefined ? tierThresholds[ i ] : '∞' })</small>
								</button>
							) ) }
						</div>
						<div className={ `food-quiz__tiers-inner active-tier-${ activeTier }` }>
							<InnerBlocks
								allowedBlocks={ [ 'planet4-child-theme-switzerland/food-quiz-result-tier' ] }
								template={ [
									[ 'planet4-child-theme-switzerland/food-quiz-result-tier', { tierIndex: 0 } ],
									[ 'planet4-child-theme-switzerland/food-quiz-result-tier', { tierIndex: 1 } ],
									[ 'planet4-child-theme-switzerland/food-quiz-result-tier', { tierIndex: 2 } ],
									[ 'planet4-child-theme-switzerland/food-quiz-result-tier', { tierIndex: 3 } ],
									[ 'planet4-child-theme-switzerland/food-quiz-result-tier', { tierIndex: 4 } ],
								] }
								templateLock="all"
							/>
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
