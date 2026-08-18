import { __ } from '@wordpress/i18n';

( function () {
	const statusLabels = {
		missing: __( 'Vermisst', 'planet4-child-theme-switzerland' ),
		endangered: __( 'Gefährdet', 'planet4-child-theme-switzerland' ),
		unknown: __( 'Unbekannt', 'planet4-child-theme-switzerland' ),
	};

	function normalizeStatus( status ) {
		return statusLabels[ status ] ? status : 'unknown';
	}

	function createElement( tagName, className, text ) {
		const element = document.createElement( tagName );
		if ( className ) {
			element.className = className;
		}
		if ( text ) {
			element.textContent = text;
		}
		return element;
	}

	function createDetailItem( label, value, className = '' ) {
		const wrapper = document.createElement( 'div' );
		const term = createElement( 'dt', '', label );
		const description = createElement( 'dd', className, value );
		wrapper.append( term, description );
		return wrapper;
	}

	function createImage( creature, className ) {
		if ( ! creature.imageUrl ) {
			return null;
		}

		const image = document.createElement( 'img' );
		image.className = className;
		image.src = creature.imageUrl;
		image.alt = creature.imageAlt || '';
		return image;
	}

	function initCampaign( root ) {
		if ( ! root || root.dataset.yearEndCampaignInitialized ) {
			return;
		}

		root.dataset.yearEndCampaignInitialized = '1';
		let creatures = [];
		let selectedId = null;

		try {
			creatures = JSON.parse( root.dataset.creatures || '[]' );
		} catch {
			return;
		}

		const grid = root.querySelector( '.year-end-campaign-2026__grid' );
		const dossierOutput = root.querySelector( '.year-end-campaign-2026__dossier-output' );
		const emptyState = root.querySelector( '.year-end-campaign-2026__empty' );
		if ( ! grid || ! dossierOutput || ! emptyState || ! Array.isArray( creatures ) ) {
			return;
		}

		const dossierLabel = root.dataset.dossierLabel || '';
		const emptyLabel = root.dataset.emptyLabel || '';
		const ctaTemplate = root.dataset.ctaTemplate || '{{name}}';
		const donationNote = root.dataset.donationNote || '';
		const tamaroAnchorId = root.dataset.tamaroAnchorId || '';

		function scrollToDossier() {
			const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
			const headerOffset = 120;
			const dossierTop = window.scrollY + dossierOutput.getBoundingClientRect().top - headerOffset;
			window.scrollTo( { top: Math.max( 0, dossierTop ), behavior: reducedMotion ? 'auto' : 'smooth' } );
		}

		function renderDossier( creature ) {
			dossierOutput.replaceChildren();
			emptyState.hidden = Boolean( creature );
			emptyState.textContent = emptyLabel;

			if ( ! creature ) {
				return;
			}

			const dossier = createElement( 'article', 'year-end-campaign-2026__dossier' );
			const details = document.createElement( 'div' );
			const label = createElement( 'p', 'year-end-campaign-2026__dossier-label', `// ${ dossierLabel }` );
			const title = createElement( 'h2', 'is-style-no-underline', creature.name || '' );
			const family = createElement( 'p' );
			const familyName = createElement( 'em', '', creature.family || '' );
			family.appendChild( familyName );
			const dataList = document.createElement( 'dl' );
			dataList.append(
				createDetailItem( __( 'Case no.', 'planet4-child-theme-switzerland' ), creature.caseNumber || '' ),
				createDetailItem(
					__( 'Status', 'planet4-child-theme-switzerland' ),
					statusLabels[ normalizeStatus( creature.status ) ],
					`year-end-campaign-2026__status year-end-campaign-2026__status--${ normalizeStatus( creature.status ) }`
				),
				createDetailItem( __( 'Depth', 'planet4-child-theme-switzerland' ), creature.depth || '' ),
				createDetailItem( __( 'Coordinates', 'planet4-child-theme-switzerland' ), creature.coordinates || '' )
			);
			const description = createElement( 'p', '', creature.description || '' );
			details.append( label, title, family, dataList, description );

			const aside = createElement( 'div', 'year-end-campaign-2026__dossier-aside' );
			const image = createImage( creature, '' );
			if ( image ) {
				aside.appendChild( image );
			}
			const cta = createElement( 'button', 'year-end-campaign-2026__cta', ctaTemplate.replace( '{{name}}', creature.name || '' ) );
			cta.type = 'button';
			cta.addEventListener( 'click', () => {
				const tamaroForm = tamaroAnchorId ? document.getElementById( tamaroAnchorId ) : null;
				if ( tamaroForm ) {
					tamaroForm.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				}
			} );
			const note = createElement( 'p', '', donationNote );
			aside.append( cta, note );
			dossier.append( details, aside );
			dossierOutput.appendChild( dossier );
		}

		function render() {
			grid.replaceChildren();
			creatures.forEach( creature => {
				const isSelected = creature.id === selectedId;
				const card = createElement( 'button', `year-end-campaign-2026__card${ isSelected ? ' is-selected' : '' }` );
				card.type = 'button';
				card.setAttribute( 'aria-pressed', String( isSelected ) );
				const imageWrapper = createElement( 'div', 'year-end-campaign-2026__image' );
				const image = createImage( creature, '' );
				if ( image ) {
					imageWrapper.appendChild( image );
				}
				const caseNumber = createElement( 'span', 'year-end-campaign-2026__case-number', creature.caseNumber || '' );
				const normalizedStatus = normalizeStatus( creature.status );
				const status = createElement(
					'span',
					`year-end-campaign-2026__status year-end-campaign-2026__status--${ normalizedStatus }`,
					statusLabels[ normalizedStatus ]
				);
				const name = createElement( 'h3', '', creature.name || '' );
				const family = createElement( 'p' );
				family.appendChild( createElement( 'em', '', creature.family || '' ) );
				const depth = createElement( 'p', '', creature.depth || '' );
				card.append( imageWrapper, caseNumber, status, name, family, depth );
				card.addEventListener( 'click', () => {
					selectedId = selectedId === creature.id ? null : creature.id;
					render();
					if ( selectedId ) {
						scrollToDossier();
					}
				} );
				grid.appendChild( card );
			} );
			renderDossier( creatures.find( creature => creature.id === selectedId ) );
		}

		render();
	}

	function initAllCampaigns() {
		document.querySelectorAll( '.year-end-campaign-2026' ).forEach( initCampaign );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAllCampaigns );
	} else {
		initAllCampaigns();
	}
} )();
