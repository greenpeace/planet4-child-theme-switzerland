import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { eyebrow, headline, intro, dossierLabel, emptyLabel, ctaTemplate, donationNote, tamaroAnchorId, creatures = [] } = attributes;
	const blockProps = useBlockProps.save( { className: 'year-end-campaign-2026' } );

	return (
		<div
			{ ...blockProps }
			data-creatures={ JSON.stringify( creatures ) }
			data-dossier-label={ dossierLabel }
			data-empty-label={ emptyLabel }
			data-cta-template={ ctaTemplate }
			data-donation-note={ donationNote }
			data-tamaro-anchor-id={ tamaroAnchorId }
		>
			<div className="year-end-campaign-2026__header">
				<p className="year-end-campaign-2026__eyebrow">{ eyebrow }</p>
				<h2 className="is-style-no-underline">{ headline }</h2>
				<p>{ intro }</p>
			</div>
			<div className="year-end-campaign-2026__grid" role="group"></div>
			<div className="year-end-campaign-2026__dossier-output" aria-live="polite"></div>
			<p className="year-end-campaign-2026__empty">{ emptyLabel }</p>
		</div>
	);
}
