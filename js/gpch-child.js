/**
 * Contains frontend JS for planet4-child-theme-switzerland
 */

// Helper function for copy to clipboard in forms
function copyTextareaToClipboard() {
	var copyText = document.querySelector( '#clipboard-copy-text' );
	copyText.select();
	document.execCommand( 'copy' );
}

// Wait for the DOM to be fully loaded
document.addEventListener( 'DOMContentLoaded', function () {
	// Replace the layout class in the listing page of publications
	if (
		document.body.classList.contains( 'tax-p4-page-type' ) &&
		( document.body.classList.contains( 'term-publikation' ) ||
			document.body.classList.contains( 'term-publication' ) )
	) {
		const listingPageContent = document.getElementById(
			'listing-page-content'
		);

		if ( listingPageContent ) {
			listingPageContent.classList.replace(
				'wp-block-query--list',
				'wp-block-query--grid'
			);
		}
	}
} );

/**
 * Measure Analytics/Tag Manager blocking
 * See: https://www.simoahava.com/analytics/measure-ad-blocker-impact-server-side-gtm/
 */
( function () {
	// Set these to the endpoints configured in the Client template
	var baitPath = 'https://sst.greenpeace.ch/ads-min.js';
	var pixelPath = 'https://sst.greenpeace.ch/4dchk';

	// Prevent the script from running multiple times per session
	if (
		typeof window.sessionStorage !== 'object' ||
		window.sessionStorage.getItem( 'gpch_blocker_checked' ) === '1'
	) {
		return;
	}

	// Inject the bait file
	const el = document.createElement( 'script' );
	el.src = baitPath;
	document.body.appendChild( el );

	let gaBlocked = false;

	// Run the detections at page load to avoid race conditions
	window.addEventListener( 'load', function () {
		// Send a HEAD request for the Universal Analytics library to see if it's blocked
		fetch( 'https://www.google-analytics.com/analytics.js', {
			method: 'HEAD',
			mode: 'no-cors',
		} )
			.catch( function () {
				// If the load failed, assume GA was blocked
				gaBlocked = true;
			} )
			.finally( function () {
				// Build the GA4 parameters, add additional parameters at your leisure
				var params = {
					ads_blocked: ! document.querySelector( '#GxsCRdhiJi' ), // Detect if the bait file was blocked
					gtm_blocked: ! (
						window.google_tag_manager &&
						window.google_tag_manager.dataLayer
					), // Detect if gtm.js was blocked
					ga_blocked: gaBlocked, // Detect if analytics.js was blocked
				};

				// Build the pixel request with a unique, random Client ID
				var cid =
					Math.floor( Math.random() * 1000000 + 1 ) +
					'_' +
					new Date().getTime();
				var img = document.createElement( 'img' );
				img.style = 'width: 1; height: 1; display: none;';
				img.src =
					pixelPath +
					'?client_id=' +
					cid +
					'&' +
					Object.keys( params )
						.reduce( function ( acc, cur ) {
							return acc.concat( cur + '=' + params[ cur ] );
						}, [] )
						.join( '&' );
				document.body.appendChild( img );

				// Save to session storage
				if ( window.sessionStorage ) {
					sessionStorage.setItem( 'gpch_blocker_checked', '1' );
				}
			} );
	} );
} )();
