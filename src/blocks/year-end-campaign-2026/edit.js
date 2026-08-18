import { __ } from '@wordpress/i18n';
import { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { Button, Notice, PanelBody, PanelRow, SelectControl, TextControl, TextareaControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { chevronDown, chevronUp, trash } from '@wordpress/icons';
import ConfirmModal from '../../components/ConfirmModal';

import './editor.scss';
import './style.scss';

const minimumCreatures = 4;
const maximumCreatures = 10;
const statusLabels = {
	missing: __( 'Vermisst', 'planet4-child-theme-switzerland' ),
	endangered: __( 'Gefährdet', 'planet4-child-theme-switzerland' ),
	unknown: __( 'Unbekannt', 'planet4-child-theme-switzerland' ),
};

function normalizeStatus( status ) {
	return statusLabels[ status ] ? status : 'unknown';
}

const createCreature = index => ( {
	id: `creature-${ Date.now() }-${ index }`,
	imageId: 0,
	imageUrl: '',
	imageAlt: '',
	name: __( 'New deep-sea animal', 'planet4-child-theme-switzerland' ),
	family: '',
	status: 'unknown',
	depth: '',
	coordinates: '',
	caseNumber: '',
	description: '',
} );

export default function Edit( { attributes, setAttributes } ) {
	const { eyebrow, headline, intro, dossierLabel, emptyLabel, ctaTemplate, donationNote, tamaroAnchorId, creatures = [] } = attributes;
	const [ selectedId, setSelectedId ] = useState( creatures[ 0 ]?.id || null );
	const [ removeIndex, setRemoveIndex ] = useState( null );
	const selectedCreature = creatures.find( creature => creature.id === selectedId ) || null;

	function updateCreature( index, key, value ) {
		setAttributes( {
			creatures: creatures.map( ( creature, creatureIndex ) => ( creatureIndex === index ? { ...creature, [ key ]: value } : creature ) ),
		} );
	}

	function updateCreatureImage( index, media ) {
		setAttributes( {
			creatures: creatures.map( ( creature, creatureIndex ) =>
				creatureIndex === index
					? {
							...creature,
							imageId: media.id || 0,
							imageUrl: media.url || '',
							imageAlt: media.alt || '',
					  }
					: creature
			),
		} );
	}

	function moveCreature( index, direction ) {
		const nextIndex = index + direction;
		if ( nextIndex < 0 || nextIndex >= creatures.length ) {
			return;
		}
		const nextCreatures = [ ...creatures ];
		[ nextCreatures[ index ], nextCreatures[ nextIndex ] ] = [ nextCreatures[ nextIndex ], nextCreatures[ index ] ];
		setAttributes( { creatures: nextCreatures } );
	}

	function addCreature() {
		if ( creatures.length >= maximumCreatures ) {
			return;
		}
		const creature = createCreature( creatures.length + 1 );
		setAttributes( { creatures: [ ...creatures, creature ] } );
		setSelectedId( creature.id );
	}

	function removeCreature() {
		if ( removeIndex === null || creatures.length <= minimumCreatures ) {
			setRemoveIndex( null );
			return;
		}
		const nextCreatures = creatures.filter( ( _, index ) => index !== removeIndex );
		setAttributes( { creatures: nextCreatures } );
		if ( selectedId === creatures[ removeIndex ].id ) {
			setSelectedId( nextCreatures[ 0 ]?.id || null );
		}
		setRemoveIndex( null );
	}

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Campaign settings', 'planet4-child-theme-switzerland' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Eyebrow', 'planet4-child-theme-switzerland' ) }
						value={ eyebrow }
						onChange={ value => setAttributes( { eyebrow: value } ) }
					/>
					<TextControl
						label={ __( 'Headline', 'planet4-child-theme-switzerland' ) }
						value={ headline }
						onChange={ value => setAttributes( { headline: value } ) }
					/>
					<TextareaControl
						label={ __( 'Introduction', 'planet4-child-theme-switzerland' ) }
						value={ intro }
						onChange={ value => setAttributes( { intro: value } ) }
					/>
					<TextControl
						label={ __( 'Dossier label', 'planet4-child-theme-switzerland' ) }
						value={ dossierLabel }
						onChange={ value => setAttributes( { dossierLabel: value } ) }
					/>
					<TextControl
						label={ __( 'Empty-state label', 'planet4-child-theme-switzerland' ) }
						value={ emptyLabel }
						onChange={ value => setAttributes( { emptyLabel: value } ) }
					/>
					<TextControl
						label={ __( 'CTA text', 'planet4-child-theme-switzerland' ) }
						help={ __( 'Use {{name}} for the selected animal name.', 'planet4-child-theme-switzerland' ) }
						value={ ctaTemplate }
						onChange={ value => setAttributes( { ctaTemplate: value } ) }
					/>
					<TextControl
						label={ __( 'Donation note', 'planet4-child-theme-switzerland' ) }
						value={ donationNote }
						onChange={ value => setAttributes( { donationNote: value } ) }
					/>
					<TextControl
						label={ __( 'Tamaro form anchor ID', 'planet4-child-theme-switzerland' ) }
						help={ __(
							'Set this to the HTML anchor ID of the separately inserted Tamaro block, without the #.',
							'planet4-child-theme-switzerland'
						) }
						value={ tamaroAnchorId }
						onChange={ value => setAttributes( { tamaroAnchorId: value } ) }
					/>
				</PanelBody>

				<PanelBody title={ __( 'Deep-sea animals', 'planet4-child-theme-switzerland' ) } initialOpen={ false }>
					<Notice status="info" isDismissible={ false }>
						{ __( 'Add between 4 and 10 animals. Select a card in the preview to view its dossier.', 'planet4-child-theme-switzerland' ) }
					</Notice>
					{ creatures.map( ( creature, index ) => (
						<div className="year-end-campaign-2026__editor-creature" key={ creature.id }>
							<h3>{ creature.name || __( 'Untitled animal', 'planet4-child-theme-switzerland' ) }</h3>
							<MediaUploadCheck>
								<MediaUpload
									allowedTypes={ [ 'image' ] }
									value={ creature.imageId }
									onSelect={ media => updateCreatureImage( index, media ) }
									render={ ( { open } ) => (
										<Button onClick={ open } variant="secondary">
											{ creature.imageUrl ? (
												<img src={ creature.imageUrl } alt="" />
											) : (
												__( 'Choose image', 'planet4-child-theme-switzerland' )
											) }
										</Button>
									) }
								/>
							</MediaUploadCheck>
							<TextControl
								label={ __( 'Name', 'planet4-child-theme-switzerland' ) }
								value={ creature.name }
								onChange={ value => updateCreature( index, 'name', value ) }
							/>
							<TextControl
								label={ __( 'Biological family', 'planet4-child-theme-switzerland' ) }
								value={ creature.family }
								onChange={ value => updateCreature( index, 'family', value ) }
							/>
							<SelectControl
								label={ __( 'Conservation status', 'planet4-child-theme-switzerland' ) }
								value={ normalizeStatus( creature.status ) }
								onChange={ value => updateCreature( index, 'status', value ) }
								options={ Object.entries( statusLabels ).map( ( [ value, label ] ) => ( { value, label } ) ) }
							/>
							<TextControl
								label={ __( 'Habitat depth', 'planet4-child-theme-switzerland' ) }
								value={ creature.depth }
								onChange={ value => updateCreature( index, 'depth', value ) }
							/>
							<TextControl
								label={ __( 'Coordinates', 'planet4-child-theme-switzerland' ) }
								value={ creature.coordinates }
								onChange={ value => updateCreature( index, 'coordinates', value ) }
							/>
							<TextControl
								label={ __( 'Case number', 'planet4-child-theme-switzerland' ) }
								value={ creature.caseNumber }
								onChange={ value => updateCreature( index, 'caseNumber', value ) }
							/>
							<TextareaControl
								label={ __( 'Description', 'planet4-child-theme-switzerland' ) }
								value={ creature.description }
								onChange={ value => updateCreature( index, 'description', value ) }
							/>
							<PanelRow>
								<Button
									icon={ chevronUp }
									label={ __( 'Move up', 'planet4-child-theme-switzerland' ) }
									showTooltip={ true }
									disabled={ index === 0 }
									onClick={ () => moveCreature( index, -1 ) }
								/>
								<Button
									icon={ chevronDown }
									label={ __( 'Move down', 'planet4-child-theme-switzerland' ) }
									showTooltip={ true }
									disabled={ index === creatures.length - 1 }
									onClick={ () => moveCreature( index, 1 ) }
								/>
								<Button
									icon={ trash }
									label={ __( 'Remove', 'planet4-child-theme-switzerland' ) }
									showTooltip={ true }
									isDestructive
									disabled={ creatures.length <= minimumCreatures }
									onClick={ () => setRemoveIndex( index ) }
								/>
							</PanelRow>
						</div>
					) ) }
					<Button variant="primary" onClick={ addCreature } disabled={ creatures.length >= maximumCreatures }>
						{ __( 'Add animal', 'planet4-child-theme-switzerland' ) }
					</Button>
				</PanelBody>
			</InspectorControls>

			<ConfirmModal
				open={ removeIndex !== null }
				title={ __( 'Remove animal?', 'planet4-child-theme-switzerland' ) }
				message={ __( 'This animal and its dossier details will be permanently removed.', 'planet4-child-theme-switzerland' ) }
				onClose={ () => setRemoveIndex( null ) }
				onConfirm={ removeCreature }
			/>

			<div { ...useBlockProps( { className: 'year-end-campaign-2026 year-end-campaign-2026--editor' } ) }>
				<div className="year-end-campaign-2026__header">
					<p className="year-end-campaign-2026__eyebrow">{ eyebrow }</p>
					<h2 className="is-style-no-underline">{ headline }</h2>
					<p>{ intro }</p>
				</div>
				<div className="year-end-campaign-2026__grid">
					{ creatures.map( creature => (
						<button
							key={ creature.id }
							type="button"
							className={ `year-end-campaign-2026__card${ creature.id === selectedId ? ' is-selected' : '' }` }
							onClick={ () => setSelectedId( creature.id ) }
						>
							<div className="year-end-campaign-2026__image">
								{ creature.imageUrl ? (
									<img src={ creature.imageUrl } alt="" />
								) : (
									<span>{ __( 'Add image', 'planet4-child-theme-switzerland' ) }</span>
								) }
							</div>
							<span className="year-end-campaign-2026__case-number">{ creature.caseNumber }</span>
							<span className={ `year-end-campaign-2026__status year-end-campaign-2026__status--${ normalizeStatus( creature.status ) }` }>
								{ statusLabels[ normalizeStatus( creature.status ) ] }
							</span>
							<h3>{ creature.name }</h3>
							<p>
								<em>{ creature.family }</em>
							</p>
							<p>{ creature.depth }</p>
						</button>
					) ) }
				</div>
				{ selectedCreature ? (
					<Dossier creature={ selectedCreature } dossierLabel={ dossierLabel } ctaTemplate={ ctaTemplate } donationNote={ donationNote } isPreview />
				) : (
					<p className="year-end-campaign-2026__empty">{ emptyLabel }</p>
				) }
			</div>
		</>
	);
}

function Dossier( { creature, dossierLabel, ctaTemplate, donationNote, isPreview = false } ) {
	const ctaLabel = ctaTemplate.replace( '{{name}}', creature.name || '' );
	return (
		<div className="year-end-campaign-2026__dossier">
			<div>
				<p className="year-end-campaign-2026__dossier-label">{ `// ${ dossierLabel }` }</p>
				<h2>{ creature.name }</h2>
				<p>
					<em>{ creature.family }</em>
				</p>
				<dl>
					<div>
						<dt>{ __( 'Case no.', 'planet4-child-theme-switzerland' ) }</dt>
						<dd>{ creature.caseNumber }</dd>
					</div>
					<div>
						<dt>{ __( 'Status', 'planet4-child-theme-switzerland' ) }</dt>
						<dd className={ `year-end-campaign-2026__status year-end-campaign-2026__status--${ normalizeStatus( creature.status ) }` }>
							{ statusLabels[ normalizeStatus( creature.status ) ] }
						</dd>
					</div>
					<div>
						<dt>{ __( 'Depth', 'planet4-child-theme-switzerland' ) }</dt>
						<dd>{ creature.depth }</dd>
					</div>
					<div>
						<dt>{ __( 'Coordinates', 'planet4-child-theme-switzerland' ) }</dt>
						<dd>{ creature.coordinates }</dd>
					</div>
				</dl>
				<p>{ creature.description }</p>
			</div>
			<div className="year-end-campaign-2026__dossier-aside">
				{ creature.imageUrl && <img src={ creature.imageUrl } alt={ creature.imageAlt } /> }
				<button type="button" className="year-end-campaign-2026__cta" disabled={ isPreview }>
					{ ctaLabel }
				</button>
				<p>{ donationNote }</p>
			</div>
		</div>
	);
}
