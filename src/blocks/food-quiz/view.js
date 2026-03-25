import { __ } from '@wordpress/i18n';

( function () {
	const getMealTitle = time => {
		switch ( time ) {
			case 'breakfast':
				return __( 'Breakfast', 'planet4-child-theme-switzerland' );
			case 'lunch':
				return __( 'Lunch', 'planet4-child-theme-switzerland' );
			case 'dinner':
				return __( 'Dinner', 'planet4-child-theme-switzerland' );
			default:
				return time;
		}
	};

	function initFoodQuiz( root ) {
		if ( ! root || root.dataset.fqInitialized ) {
			return;
		}

		root.dataset.fqInitialized = '1';

		try {
			const breakfastMeals = JSON.parse( root.getAttribute( 'data-breakfast-meals' ) || '[]' );
			const lunchMeals = JSON.parse( root.getAttribute( 'data-lunch-meals' ) || '[]' );
			const dinnerMeals = JSON.parse( root.getAttribute( 'data-dinner-meals' ) || '[]' );
			const drinks = JSON.parse( root.getAttribute( 'data-drinks' ) || '[]' );
			const tierThresholds = JSON.parse( root.getAttribute( 'data-tier-thresholds' ) || '[]' );
			const tierLabels = JSON.parse( root.getAttribute( 'data-tier-labels' ) || '[]' );

			const mealTimes = [ 'breakfast', 'lunch', 'dinner' ];

			const mealContainer = root.querySelector( '.food-quiz__meal-times' );
			const drinksContainer = root.querySelector( '.food-quiz__drinks' );
			const calculateButton = root.querySelector( '.food-quiz__calculate' );
			const resetButton = root.querySelector( '.food-quiz__reset' );
			const outputContainer = root.querySelector( '.food-quiz__result-output' );
			const errorElement = outputContainer && outputContainer.querySelector( '.error-message' );

			if ( calculateButton ) {
				const label = __( 'Calculate', 'planet4-child-theme-switzerland' );
				calculateButton.textContent = label;
				calculateButton.setAttribute( 'aria-label', label );
			}

			if ( resetButton ) {
				const label = __( 'Reset', 'planet4-child-theme-switzerland' );
				resetButton.textContent = label;
				resetButton.setAttribute( 'aria-label', label );
			}

			if ( errorElement ) {
				errorElement.textContent = __( 'Please select at least one of each meal.', 'planet4-child-theme-switzerland' );
			}

			// Debounced auto-calc helper — only active after first manual Calculate
			let debounceTimer = null;
			const DEBOUNCE_DELAY = 300;
			let autoCalcEnabled = false;
			let activeTier = null;
			let resizeRafId = null;

			// Show a spinner inside the calculate button for a short delay,
			// then run the calculation. Safe to call repeatedly; it will
			// not append duplicate spinner nodes.
			function showSpinnerThenCalculate( isManual = false ) {
				if ( ! calculateButton ) {
					// fallback: call directly
					calculateAndShow( isManual );
					return;
				}

				// If a spinner is already present, don't add another
				if ( calculateButton.querySelector( '.fq-spinner' ) ) {
					// still enforce a short delay before recalculation
					setTimeout( () => {
						calculateAndShow( isManual );
					}, 500 );
					return;
				}

				// Build inline SVG spinner
				const spinner = document.createElement( 'span' );
				spinner.className = 'fq-spinner';
				spinner.setAttribute( 'aria-hidden', 'true' );
				spinner.style.display = 'inline-block';
				spinner.style.marginLeft = '0.5rem';
				spinner.innerHTML = `
					<svg width="16" height="16" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
						<circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4">
							<animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.7s" repeatCount="indefinite" />
						</circle>
					</svg>
				`;

				calculateButton.appendChild( spinner );
				calculateButton.setAttribute( 'aria-busy', 'true' );

				setTimeout( () => {
					try {
						calculateAndShow( isManual );
					} finally {
						// Remove spinner
						const s = calculateButton.querySelector( '.fq-spinner' );
						if ( s ) {
							s.remove();
						}
						calculateButton.removeAttribute( 'aria-busy' );
						calculateButton.disabled = true;
					}
				}, 500 );
			}

			function debouncedCalculateAndShow() {
				if ( ! autoCalcEnabled ) {
					return;
				} // Gate: do nothing until user manually triggers calc for the first time

				if ( debounceTimer ) {
					clearTimeout( debounceTimer );
				}

				debounceTimer = setTimeout( () => {
					try {
						// Show spinner then calculate
						showSpinnerThenCalculate();
					} catch ( e ) {
						// ignore
					}
				}, DEBOUNCE_DELAY );
			}

			function render() {
				if ( ! mealContainer ) {
					return;
				}

				// render meal options for each time
				mealTimes.forEach( time => {
					const el = mealContainer.querySelector( `.food-quiz__meal[data-time="${ time }"]` );

					if ( ! el ) {
						return;
					}

					const title = getMealTitle( time );
					el.setAttribute( 'aria-label', title );
					const mealH3 = document.createElement( 'h3' );
					mealH3.textContent = title;
					el.appendChild( mealH3 );

					const optionWrapper = document.createElement( 'div' );
					optionWrapper.className = 'food-quiz__meal-options';
					el.appendChild( optionWrapper );

					/* eslint-disable-next-line no-nested-ternary */
					const set = time === 'breakfast' ? breakfastMeals : time === 'lunch' ? lunchMeals : dinnerMeals;
					set.forEach( ( m, idx ) => {
						const label = document.createElement( 'label' );
						label.className = 'fq-option';

						const radio = document.createElement( 'input' );
						radio.type = 'radio';
						radio.name = `fq-${ time }`;
						radio.value = String( idx );
						label.appendChild( radio );

						const titleP = document.createElement( 'p' );
						titleP.className = 'fq-option-title';
						titleP.textContent = m.title;
						label.appendChild( titleP );

						const imgDiv = document.createElement( 'div' );
						imgDiv.className = 'fq-option-img';
						if ( m.imageUrl ) {
							try {
								const parsedUrl = new URL( m.imageUrl );
								if ( parsedUrl.protocol === 'https:' || parsedUrl.protocol === 'http:' ) {
									const img = document.createElement( 'img' );
									img.src = parsedUrl.href;
									img.alt = '';
									imgDiv.appendChild( img );
								}
							} catch ( e ) {
								// Invalid URL, skip image
							}
						}
						label.appendChild( imgDiv );

						optionWrapper.appendChild( label );

						// Auto-recalculate when a meal option changes

						if ( radio ) {
							radio.addEventListener( 'change', () => {
								if ( calculateButton ) {
									// User changed an input -> allow manual recalc
									calculateButton.disabled = false;
								}

								// Only trigger auto-recalc if user has pressed Calculate at least once
								if ( autoCalcEnabled ) {
									debouncedCalculateAndShow();
								}
							} );
						}
					} );
				} );

				// render drinks
				if ( drinksContainer ) {
					const drinksTitle = __( 'Drinks', 'planet4-child-theme-switzerland' );
					const drinksH3 = document.createElement( 'h3' );
					drinksH3.textContent = drinksTitle;
					drinksContainer.appendChild( drinksH3 );

					const optionWrapper = document.createElement( 'div' );
					optionWrapper.className = 'food-quiz__drink-options';
					drinksContainer.appendChild( optionWrapper );

					drinks.forEach( ( d, idx ) => {
						const decreaseLabel = __( 'Decrease', 'planet4-child-theme-switzerland' );
						const increaseLabel = __( 'Increase', 'planet4-child-theme-switzerland' );
						const drinkOption = document.createElement( 'div' );
						drinkOption.className = 'fq-drink-wrapper';

						const drinkLabel = document.createElement( 'div' );
						drinkLabel.className = 'fq-drink-label';

						const titleP = document.createElement( 'p' );
						titleP.className = 'fq-option-title';
						titleP.textContent = d.title;

						const drinkControls = document.createElement( 'div' );
						drinkControls.className = 'fq-drink-controls';

						const dec = document.createElement( 'button' );
						dec.type = 'button';
						dec.className = 'fq-drink-decrease';
						dec.setAttribute( 'aria-label', decreaseLabel );
						dec.textContent = '−';

						const inputEl = document.createElement( 'input' );
						inputEl.type = 'number';
						inputEl.min = '0';
						inputEl.max = '10';
						inputEl.value = '0';
						inputEl.dataset.index = idx;
						inputEl.className = 'fq-drink-input';

						const inc = document.createElement( 'button' );
						inc.type = 'button';
						inc.className = 'fq-drink-increase';
						inc.setAttribute( 'aria-label', increaseLabel );
						inc.textContent = '+';

						drinkControls.appendChild( dec );
						drinkControls.appendChild( inputEl );
						drinkControls.appendChild( inc );
						drinkLabel.appendChild( titleP );
						drinkLabel.appendChild( drinkControls );
						drinkOption.appendChild( drinkLabel );

						optionWrapper.appendChild( drinkOption );

						if ( dec && inputEl ) {
							dec.addEventListener( 'click', () => {
								let v = Number( inputEl.value ) || 0;
								v = Math.max( 0, v - 1 );
								inputEl.value = v;
								inputEl.dispatchEvent( new Event( 'input', { bubbles: true } ) );
							} );
						}
						if ( inc && inputEl ) {
							inc.addEventListener( 'click', () => {
								let v = Number( inputEl.value ) || 0;
								v = Math.min( 10, v + 1 );
								inputEl.value = v;
								inputEl.dispatchEvent( new Event( 'input', { bubbles: true } ) );
							} );
						}

						// Auto-recalculate when drink input changes
						if ( inputEl ) {
							inputEl.addEventListener( 'input', () => {
								if ( calculateButton ) {
									// User changed an input -> allow manual recalc
									calculateButton.disabled = false;
								}

								// Only trigger auto-recalc if user has pressed Calculate at least once
								if ( autoCalcEnabled ) {
									debouncedCalculateAndShow();
								}
							} );
						}
					} );
				}
			}

			// Sync tier labels and class names from context to the result scale and tier titles
			function syncTierTitles() {
				if ( ! outputContainer || ! Array.isArray( tierLabels ) ) {
					return;
				}

				outputContainer.querySelectorAll( '.food-quiz__tier' ).forEach( tierEl => {
					const tier = parseInt( tierEl.getAttribute( 'data-tier' ), 10 );

					if ( Number.isNaN( tier ) ) {
						return;
					}

					const titleEl = tierEl.querySelector( '.food-quiz__tier-title' );
					if ( titleEl ) {
						const tierClassPattern = /result-scale__tier--\d+/g;
						titleEl.className = titleEl.className.replace( tierClassPattern, '' ).replace( /\s+/g, ' ' ).trim();
						titleEl.classList.add( `result-scale__tier--${ tier }` );
					}

					const labelEl = tierEl.querySelector( '.food-quiz__tier-title-label' );
					if ( labelEl ) {
						labelEl.textContent = tierLabels[ tier ] || '';
					}
				} );
			}

			// Create result scale / arrow UI
			function ensureResultScale() {
				if ( ! outputContainer ) {
					return null;
				}

				const scaleRoot = outputContainer.querySelector( '.result-scale' );

				if ( ! scaleRoot ) {
					return null;
				}

				if ( scaleRoot.dataset.fqScaleInit ) {
					return scaleRoot;
				}

				scaleRoot.dataset.fqScaleInit = '1';

				// Build tiers
				scaleRoot.innerHTML = '';
				const row = document.createElement( 'div' );
				row.className = 'result-scale__row';

				for ( let i = 0; i < 5; i++ ) {
					const t = document.createElement( 'div' );
					t.className = `result-scale__tier result-scale__tier--${ i }`;
					t.setAttribute( 'data-tier', String( i ) );
					t.setAttribute( 'aria-hidden', 'true' );
					const labelText = Array.isArray( tierLabels ) && tierLabels[ i ] ? tierLabels[ i ] : `Tier ${ i + 1 }`;
					const span = document.createElement( 'span' );
					span.className = 'result-scale__label';
					span.textContent = labelText;
					t.appendChild( span );
					row.appendChild( t );
				}

				// Arrow
				const arrow = document.createElement( 'div' );
				arrow.className = 'result-scale__arrow';
				arrow.setAttribute( 'aria-hidden', 'true' );
				scaleRoot.appendChild( arrow );
				scaleRoot.appendChild( row );

				return scaleRoot;
			}

			function moveArrowToTier( tier ) {
				const scaleRoot = outputContainer && outputContainer.querySelector( '.result-scale' );

				if ( ! scaleRoot ) {
					return;
				}

				const tiers = scaleRoot.querySelectorAll( '.result-scale__tier' );
				const arrow = scaleRoot.querySelector( '.result-scale__arrow' );

				if ( ! tiers.length || ! arrow ) {
					return;
				}

				const target = tiers[ Math.max( 0, Math.min( tiers.length - 1, tier ) ) ];

				// compute center x relative to scaleRoot
				const rootRect = scaleRoot.getBoundingClientRect();
				const targetRect = target.getBoundingClientRect();
				const center = targetRect.left - rootRect.left + targetRect.width / 2;

				// adjust so arrow center aligns; arrow has no width, but transform translateX will move it
				arrow.style.transform = `translateX(${ Math.round( center ) }px)`;
			}

			function realignArrowIfNeeded() {
				if ( activeTier === null || activeTier === undefined ) {
					return;
				}

				if ( ! outputContainer ) {
					return;
				}

				const scaleElement = outputContainer.querySelector( '.result-scale' );
				if ( ! scaleElement || scaleElement.style.display === 'none' ) {
					return;
				}

				moveArrowToTier( activeTier );
			}

			// Schedule an arrow realign on the next animation frame, cancelling any previously scheduled realign.
			// This is used to debounce realigns during window resizing.
			function scheduleArrowRealign() {
				if ( resizeRafId ) {
					cancelAnimationFrame( resizeRafId );
				}

				resizeRafId = requestAnimationFrame( () => {
					resizeRafId = null;
					realignArrowIfNeeded();
				} );
			}

			// initialize scale now so it exists before first calculation
			ensureResultScale();
			window.addEventListener( 'resize', scheduleArrowRealign );
			window.addEventListener( 'orientationchange', scheduleArrowRealign );

			render();
			syncTierTitles();

			function calculateAndShow( isManual = false ) {
				let total = 0;

				// Track selected meals for datalayer
				const selectedMeals = {
					breakfast: null,
					lunch: null,
					dinner: null,
				};

				mealTimes.forEach( time => {
					const checked = root.querySelector( `input[name="fq-${ time }"]:checked` );

					if ( checked ) {
						const idx = parseInt( checked.value, 10 );
						/* eslint-disable-next-line no-nested-ternary */
						const set = time === 'breakfast' ? breakfastMeals : time === 'lunch' ? lunchMeals : dinnerMeals;

						if ( set[ idx ] ) {
							// Store selected meal title for tracking
							selectedMeals[ time ] = set[ idx ].title || `Option ${ idx + 1 }`;

							if ( set[ idx ].score ) {
								total += Number( set[ idx ].score );
							}
						}
					}
				} );

				// Track drink counts for datalayer (indexed by drink number)
				const drinkCounts = [];

				root.querySelectorAll( '.fq-drink-input' ).forEach( input => {
					const idx = parseInt( input.getAttribute( 'data-index' ), 10 );
					const servings = Number( input.value ) || 0;

					if ( drinks[ idx ] ) {
						drinkCounts[ idx ] = servings;

						if ( drinks[ idx ].score ) {
							total += servings * Number( drinks[ idx ].score );
						}
					}
				} );

				// Compute thresholds based on max possible
				const breakfastMax = Math.max( 0, ...breakfastMeals.map( m => Number( m.score ) || 0 ) );
				const lunchMax = Math.max( 0, ...lunchMeals.map( m => Number( m.score ) || 0 ) );
				const dinnerMax = Math.max( 0, ...dinnerMeals.map( m => Number( m.score ) || 0 ) );
				const drinkMax = drinks.reduce( ( sum, d ) => sum + ( Number( d.score ) || 0 ), 0 );

				const maxPossible = breakfastMax + lunchMax + dinnerMax + drinkMax * 10; // assume max 10 servings per drink

				// If thresholds are configured, pick the first tier whose upper threshold >= total.
				let tier = 4;

				if ( Array.isArray( tierThresholds ) && tierThresholds.length ) {
					for ( let i = 0; i < Math.min( 4, tierThresholds.length ); i++ ) {
						const t = tierThresholds[ i ];

						if ( typeof t === 'number' && ! Number.isNaN( t ) ) {
							if ( total <= t ) {
								tier = i;
								break;
							}
						}
					}
				} else {
					const percent = maxPossible > 0 ? total / maxPossible : 0;
					tier = Math.min( 4, Math.floor( percent * 5 ) );
				}

				// validate that at least one option for each meal is selected
				let allMealsSelected = true;
				mealTimes.forEach( time => {
					const checked = root.querySelector( `input[name="fq-${ time }"]:checked` );
					if ( ! checked ) {
						allMealsSelected = false;
					}
				} );

				if ( outputContainer ) {
					// hide all tiers first
					outputContainer.querySelectorAll( '.food-quiz__tier' ).forEach( el => ( el.style.display = 'none' ) );

					const outputErrorElement = outputContainer.querySelector( '.error-message' );
					const scaleElement = outputContainer.querySelector( '.result-scale' );

					if ( ! allMealsSelected ) {
						activeTier = null;
						if ( outputErrorElement ) {
							outputErrorElement.style.display = 'block';
						}
						if ( scaleElement ) {
							scaleElement.style.display = 'none';
						}

						if ( calculateButton ) {
							calculateButton.disabled = false;
						}

						// don't show any tier
					} else {
						if ( outputErrorElement ) {
							outputErrorElement.style.display = 'none';
						}
						if ( scaleElement ) {
							scaleElement.style.display = 'block';
						}

						const match = outputContainer.querySelector( `.food-quiz__tier[data-tier="${ tier }"]` );
						if ( match ) {
							match.style.display = 'block';
						}

						// move arrow to the resulting tier (animated)
						try {
							activeTier = tier;
							moveArrowToTier( tier );
						} catch ( e ) {
							// ignore
						}

						if ( calculateButton ) {
							calculateButton.disabled = true;
						}

						// Send tracking event to dataLayer
						if ( typeof window.dataLayer !== 'undefined' ) {
							// Build drink parameters as drinks_1, drinks_2, etc.
							const drinkParams = {};

							drinkCounts.forEach( ( count, index ) => {
								drinkParams[ `drinks_${ index + 1 }` ] = count || 0;
							} );

							window.dataLayer.push( {
								event: 'food_quiz_calculated',
								food_quiz: {
									total_score: total,
									result_tier: tier + 1,
									breakfast_meal: selectedMeals.breakfast,
									lunch_meal: selectedMeals.lunch,
									dinner_meal: selectedMeals.dinner,
									trigger_type: isManual ? 'manual' : 'automatic',
									...drinkParams,
								},
							} );
						}
					}
				}
			}

			if ( calculateButton ) {
				calculateButton.addEventListener( 'click', () => {
					// Enable auto-calc from now on
					autoCalcEnabled = true;

					if ( debounceTimer ) {
						clearTimeout( debounceTimer );
					}

					// show spinner then calculate (manual trigger)
					showSpinnerThenCalculate( true );
				} );
			}

			if ( resetButton ) {
				resetButton.addEventListener( 'click', () => {
					// Clear all meal radio selections
					root.querySelectorAll( 'input[type="radio"]' ).forEach( radio => {
						radio.checked = false;
					} );

					// Reset all drink inputs to 0
					root.querySelectorAll( '.fq-drink-input' ).forEach( input => {
						input.value = 0;
					} );

					// Hide result output
					if ( outputContainer ) {
						outputContainer.querySelectorAll( '.food-quiz__tier' ).forEach( el => ( el.style.display = 'none' ) );
						const resetErrorElement = outputContainer.querySelector( '.error-message' );
						if ( resetErrorElement ) {
							resetErrorElement.style.display = 'none';
						}
						const scaleElement = outputContainer.querySelector( '.result-scale' );
						if ( scaleElement ) {
							scaleElement.style.display = 'none';
						}
					}

					activeTier = null;

					// Stop auto-calc and re-enable Calculate button
					autoCalcEnabled = false;
					if ( debounceTimer ) {
						clearTimeout( debounceTimer );
					}
					if ( calculateButton ) {
						calculateButton.disabled = false;
					}
				} );
			}

			// initial hide all tiers
			if ( outputContainer ) {
				outputContainer.querySelectorAll( '.food-quiz__tier' ).forEach( el => ( el.style.display = 'none' ) );
			}
		} catch ( e ) {
			// ignore
		}
	}

	function initAll() {
		const blocks = document.querySelectorAll( '.wp-block-planet4-child-theme-switzerland-food-quiz, .food-quiz' );
		blocks.forEach( initFoodQuiz );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}

	// Auto-init blocks that are dynamically inserted (editor preview, AJAX, etc.)
	const observer = new MutationObserver( mutations => {
		mutations.forEach( mutation => {
			mutation.addedNodes.forEach( node => {
				if ( node.nodeType !== 1 ) {
					return;
				} // ELEMENT_NODE

				// If the added node itself is a block root
				if ( node.matches && ( node.matches( '.wp-block-planet4-child-theme-switzerland-food-quiz' ) || node.matches( '.food-quiz' ) ) ) {
					initFoodQuiz( node );
					return;
				}

				// Otherwise, search within the added subtree
				if ( node.querySelectorAll ) {
					const found = node.querySelectorAll( '.wp-block-planet4-child-theme-switzerland-food-quiz, .food-quiz' );
					found.forEach( initFoodQuiz );
				}
			} );
		} );
	} );

	observer.observe( document.body || document.documentElement, { childList: true, subtree: true } );
} )();
