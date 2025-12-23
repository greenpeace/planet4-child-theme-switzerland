( function () {
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

			const mealTimes = [ 'breakfast', 'lunch', 'dinner' ];

			const mealContainer = root.querySelector( '.food-quiz__meal-times' );
			const drinksContainer = root.querySelector( '.food-quiz__drinks' );
			const calculateButton = root.querySelector( '.food-quiz__calculate' );
			const outputContainer = root.querySelector( '.food-quiz__result-output' );

			function render() {
				if ( ! mealContainer ) return;

				// render meal options for each time
				mealTimes.forEach( ( time ) => {
					const el = mealContainer.querySelector( `.food-quiz__meal[data-time="${ time }"]` );

					if ( ! el ) {
						return;
					}

					el.innerHTML = '<h3>' + el.dataset.title + '</h3>';

					const optionWrapper = document.createElement( 'div' );
					optionWrapper.className = 'food-quiz__meal-options';
					el.appendChild( optionWrapper );

					const set = time === 'breakfast' ? breakfastMeals : time === 'lunch' ? lunchMeals : dinnerMeals;
					set.forEach( ( m, idx ) => {
						const label = document.createElement( 'label' );
						label.className = 'fq-option';
						label.innerHTML = `
								<input type="radio" name="fq-${ time }" value="${ idx }" ${ idx === 0 && time === 'breakfast' ? 'checked' : '' } />
								<p class="fq-option-title">${ m.title }</p>
								<div class="fq-option-img">${ m.imageUrl ? `<img src="${ m.imageUrl }" alt=""/>` : '' }</div>
							`;
						optionWrapper.appendChild( label );
					} );
				} );

				// render drinks
				if ( drinksContainer ) {
					drinksContainer.innerHTML = '';
					drinks.forEach( ( d, idx ) => {
						const row = document.createElement( 'div' );
						row.className = 'fq-drink-row';
						row.innerHTML = `<label>${ d.title } <input type="number" min="0" value="0" data-index="${ idx }" class="fq-drink-input" /></label>`;
						drinksContainer.appendChild( row );
					} );
				}
			}

			render();

			function calculateAndShow() {
				let total = 0;
				mealTimes.forEach( ( time ) => {
					const checked = root.querySelector( `input[name="fq-${ time }"]:checked` );
					if ( checked ) {
						const idx = parseInt( checked.value, 10 );
						const set = time === 'breakfast' ? breakfastMeals : time === 'lunch' ? lunchMeals : dinnerMeals;
						if ( set[ idx ] && set[ idx ].score ) {
							total += Number( set[ idx ].score );
						}
					}
				} );
				root.querySelectorAll( '.fq-drink-input' ).forEach( ( input ) => {
					const idx = parseInt( input.getAttribute( 'data-index' ), 10 );
					const servings = Number( input.value ) || 0;
					if ( drinks[ idx ] && drinks[ idx ].score ) {
						total += servings * Number( drinks[ idx ].score );
					}
				} );

				// compute thresholds based on max possible
				const breakfastMax = Math.max( 0, ...breakfastMeals.map( ( m ) => Number( m.score ) || 0 ) );
				const lunchMax = Math.max( 0, ...lunchMeals.map( ( m ) => Number( m.score ) || 0 ) );
				const dinnerMax = Math.max( 0, ...dinnerMeals.map( ( m ) => Number( m.score ) || 0 ) );
				const mealMaxTotal = breakfastMax + lunchMax + dinnerMax;
				const drinkMax = drinks.reduce( ( sum, d ) => sum + ( Number( d.score ) || 0 ), 0 );
				const maxPossible = mealMaxTotal + drinkMax * 5; // assume max 5 servings per drink
				const pct = maxPossible > 0 ? total / maxPossible : 0;
				const tier = Math.min( 4, Math.floor( pct * 5 ) );

				// show matching tier content
				if ( outputContainer ) {
					outputContainer
						.querySelectorAll( '.food-quiz__tier' )
						.forEach( ( el ) => ( el.style.display = 'none' ) );
					const match = outputContainer.querySelector( `.food-quiz__tier[data-tier="${ tier }"]` );
					if ( match ) {
						match.style.display = '';
					}
				}
			}

			if ( calculateButton ) {
				calculateButton.addEventListener( 'click', calculateAndShow );
			}

			// initial hide all tiers
			if ( outputContainer ) {
				outputContainer.querySelectorAll( '.food-quiz__tier' ).forEach( ( el ) => ( el.style.display = 'none' ) );
			}
		} catch ( e ) {
			console.error( 'Food Quiz error', e );
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
	const observer = new MutationObserver( ( mutations ) => {
		mutations.forEach( ( mutation ) => {
			mutation.addedNodes.forEach( ( node ) => {
				if ( node.nodeType !== 1 ) return; // ELEMENT_NODE

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
