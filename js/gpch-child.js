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


// Helper function for copy to clipboard in forms
function copyTextareaToClipboard() {
	var copyText = document.querySelector("#clipboard-copy-text");
	copyText.select();
	document.execCommand("copy");
}

/**
 * Measure Analytics/Tag Manager blocking
 * See: https://www.simoahava.com/analytics/measure-ad-blocker-impact-server-side-gtm/
 */
(function() {
	// Set these to the endpoints configured in the Client template
	var baitPath = 'https://sst.greenpeace.ch/ads-min.js';
	var pixelPath = 'https://sst.greenpeace.ch/4dchk';
	
	// Prevent the script from running multiple times per session
	if (typeof window.sessionStorage !== 'object' || window.sessionStorage.getItem('gpch_blocker_checked') === '1') {
		return;
	}
	
	// Inject the bait file
	var el = document.createElement('script');
	el.src = baitPath;
	document.body.appendChild(el);
	
	var gaBlocked = false;
	
	// Run the detections at page load to avoid race conditions
	window.addEventListener('load', function() {
		// Send a HEAD request for the Universal Analytics library to see if it's blocked
		fetch('https://www.google-analytics.com/analytics.js', {method: 'HEAD', mode: 'no-cors'})
		.catch(function() {
			// If the load failed, assume GA was blocked
			gaBlocked = true;
		})
		.finally(function() {
			// Build the GA4 parameters, add additional parameters at your leisure
			var params = {
				ads_blocked: !document.querySelector('#GxsCRdhiJi'), // Detect if the bait file was blocked
				gtm_blocked: !(window.google_tag_manager && window.google_tag_manager.dataLayer), // Detect if gtm.js was blocked
				ga_blocked: gaBlocked // Detect if analytics.js was blocked
			};
			
			// Build the pixel request with a unique, random Client ID
			var cid = Math.floor((Math.random() * 1000000) + 1) + '_' + new Date().getTime();
			var img = document.createElement('img');
			img.style = 'width: 1; height: 1; display: none;';
			img.src = pixelPath + '?client_id=' + cid + '&' + Object.keys(params).reduce(function(acc, cur) { return acc.concat(cur + '=' + params[cur]);}, []).join('&');
			document.body.appendChild(img);
			
			// Save to session storage
			if (window.sessionStorage) {
				sessionStorage.setItem('gpch_blocker_checked', '1');
			}
		});
	});
})();


/**
 * General Newsletter subscription form. Transfer values from checkboxes into hidden field.
 * Needs
 * - A checkboxes field (multiple checkboxes) with a class name newsletter-lists-field and checkbox values need to be set to the list names
 * - A hidden input field with the class newsletter-subscription-field that will contain the comma separated list of email lists
 */
(function () {
	const gpchNewsletterListsField = document.querySelector('.newsletter-lists-field');
	const gpchNewsletterCheckboxes = gpchNewsletterListsField.querySelectorAll(":scope input[type='checkbox']");
	const gpchNewsletterField = document.querySelector('.newsletter-subscription-field input');
	
	function gpchUpdateNewsletterLists() {
		let lists = '';
		
		gpchNewsletterCheckboxes.forEach((element) => {
			if (element.checked) {
				lists += element.value + ',';
			}
		});
		
		// Remove last comma
		lists = lists.slice(0, -1);
		
		// Update the lists field
		gpchNewsletterField.value = lists;
	}
	
	gpchNewsletterCheckboxes.forEach((element) => {
		element.addEventListener('change', function () {
			gpchUpdateNewsletterLists();
		});
	});
	
	gpchUpdateNewsletterLists();
})();