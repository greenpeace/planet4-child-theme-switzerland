const gpchChildThemeScripts = function() {
	const tagList = {
		'#Klima': 'tag-klima',
		'#Climat': 'tag-climat',
		'#CO2Gesetz': 'tag-co2-gesetz',
		'#LoiCO2': 'tag-loi-co2',
		'#Ernährung': 'tag-ernaehrung',
		'#Nutrition': 'tag-nutrition', // changed to alimentation, leaving it here for backwards compatibility
		'#Alimentation': 'tag-alimentation',
		'#NachhaltigerFinanzplatz': 'tag-nachhaltiger-finanzplatz',
		'#FinanceDurable': 'tag-finance-durable',
		'#GletscherInitiative': 'tag-gletscher-initiative',
		'#InitiativeGlaciers': 'tag-initiative-glaciers',
		'#Klimabewegung': 'tag-klimabewegung',
		'#MouvementClimatique': 'tag-mouvement-climatique',
		'#Klimagerechtigkeit': 'tag-klimagerechtigkeit',
		'#JusticeClimatique': 'tag-justice-climatique',
		'#ZeroWaste': 'tag-zero-waste',
		'#ZeroDechet': 'tag-zero-dechet',
		'#Reparieren': 'tag-reparieren',
		'#Réparer': 'tag-reparer',
		'#Antarktis': 'tag-antarktis',
		'#Antarctique': 'tag-antarctique',
		'#Arktis': 'tag-arktis',
		'#Arctique': 'tag-arctique',
		'#Chemie': 'tag-chemie',
		'#Toxiques': 'tag-toxiques',
		'#Energie': 'tag-energie',
		'#Landwirtschaft': 'tag-landwirtschaft',
		'#Agriculture': 'tag-agriculture',
		'#Meer': 'tag-meer',
		'#Océans': 'tag-oceans',
		'#Wald': 'tag-wald',
		'#Fôrets': 'tag-forets',
		'#Konsum': 'tag-konsum',
		'#Consommation': 'tag-consommation',
		'#Ukraine': 'tag-ukraine'
	}
	
	let lastTakeActionCoversEvent = Date.now();
	
	// How often the observer is able to trigger the update script in ms
	const takeActionCoversEventFrequency = 500;
	
	const init = () => {
		takeActionCovers();
		startObserveDOMTakeAction();

		campaignCovers();
		archivePageTags();
	}
	
	// Observe DOM element for changes
	// https://stackoverflow.com/questions/3219758/detect-changes-in-the-dom
	const observeDOM = (function(){
		var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
		
		return function( obj, callback ){
			if( !obj || obj.nodeType !== 1 ) return;
			
			if( MutationObserver ){
				// define a new observer
				var mutationObserver = new MutationObserver(callback)
				
				// have the observer observe foo for changes in children
				mutationObserver.observe( obj, { childList:true, subtree:true })
				return mutationObserver
			}
			
			// browser support fallback
			else if( window.addEventListener ){
				obj.addEventListener('DOMNodeInserted', callback, false)
				obj.addEventListener('DOMNodeRemoved', callback, false)
			}
		}
	})();
	
	const startObserveDOMTakeAction = ( function() {
		// Observe the take action covers block for changes and add classes when new elements are added (load more functionality)
		const actCoverBlocks = document.querySelectorAll(
		  '.take-action-covers-block' );
		
		actCoverBlocks.forEach( ( element ) => {
			observeDOM( element, function() {
				if( lastTakeActionCoversEvent + takeActionCoversEventFrequency < Date.now() ) {
					lastTakeActionCoversEvent = Date.now();
					takeActionCovers();
				}
			} );
		} );
	} );
	
	// Insert tag classes in action covers block
	const takeActionCovers = () => {
		const coversElements = document.querySelectorAll('.covers-block .cover-card');
		
		coversElements.forEach(( element ) => {
			const tagName = "#" + element.querySelector(':scope .cover-card-tag').textContent.trim();
			
			if (tagName in tagList) {
				element.classList.add(tagList[tagName])
			}
			
			// While we're at it, add the # in front of the tag
			const tags = element.querySelectorAll(':scope .cover-card-tag');
			
			tags.forEach( ( tag ) => {
				if (tag.textContent.indexOf('#') < 0) {
					tag.textContent = '#' + tag.textContent;
				}
			} );
		});
	};
	
	// Insert tag classes in campaign covers block
	const campaignCovers = () => {
		const coversElements = document.querySelectorAll('.campaign-covers-block  .campaign-card-column' );
		
		coversElements.forEach( ( element ) => {
			const tagName = element.querySelector( ':scope .yellow-cta' ).textContent.trim();

			if ( tagName in tagList ) {
				element.classList.add( tagList[tagName] );
			}
		} );
	};
	
	// Insert tag classes in archive pages
	const archivePageTags = () => {
		const resultsElements = document.querySelectorAll( 'body.archive .multiple-search-result .search-result-list-item ' );
		
		resultsElements.forEach( ( element ) => {
			const tagName = element.querySelector( ':scope .search-result-item-tag' ).textContent.trim();
			
			if( tagName in tagList ) {
				element.classList.add( tagList[ tagName ] );
			}
		} );
	};
	
	init();
};

gpchChildThemeScripts();


jQuery(document).ready(function () {
	// Prefill email fields in forms
	var emailConnectFields =  jQuery( "input[value='form_connect_email']" );

	if (emailConnectFields.length > 0) {
		var data = {
			'action': 'gpch_gf_prefill_field',
			'field': 'session_email'
		};
		
		jQuery.ajax({
			url: gpchData.ajaxurl,
			type: 'GET',
			data: data,
			success: function (response) {
				jQuery(emailConnectFields).val(response.data.email);
			},
			error: function () {
				jQuery(emailConnectFields).val('');
			}
		});
	}
});


// Helper function for copy to clipboard in forms
function copyTextareaToClipboard() {
	var copyText = document.querySelector("#clipboard-copy-text");
	copyText.select();
	document.execCommand("copy");
}
