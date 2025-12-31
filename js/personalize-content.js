/**
 * Replace placeholders in page content with URL parameters.
 * Supports:
 *  - {{param}}                      → value or empty
 *  - {{param|Fallback}}             → value or Fallback
 *  - {{param?IFSET|IFNOT}}          → conditional; use [[value]] inside IFSET
 */
( function () {
	const params = new URLSearchParams( window.location.search );

	// Robust regex: erlaubt fast alles in IFSET/IFNOT, endet erst an nächstem "}}"
	const RX = /\{\{\s*([a-zA-Z0-9_-]+)(?:\?((?:(?!\}\}|\|)[\s\S])*)(?:\|((?:(?!\}\})[\s\S])*))?|\|((?:(?!\}\})[\\s\S])*))?\s*\}\}/g;

	function resolvePlaceholder( _, name, ifSet, ifNotSet1, ifNotSet2 ) {
		const raw = params.get( name );
		const hasVal = raw !== null && raw.trim() !== '';
		// Clean the URL parameter value from html
		const safeVal = hasVal ? raw.replace( /[<>\u0000-\u001F]/g, '' ) : '';

		if ( hasVal ) {
			if ( ifSet !== undefined ) {
				// Replace all [[value]]-tokens
				return ifSet.replace( /\[\[value\]\]/g, safeVal );
			}
			return safeVal;
		} else {
			const fb = ifNotSet1 !== undefined ? ifNotSet1 : ifNotSet2;
			return ( fb || '' ).trim();
		}
	}

	function replaceInTextNodes( root ) {
		const walker = document.createTreeWalker( root, NodeFilter.SHOW_TEXT, null );
		const nodes = [];
		let n;
		while ( ( n = walker.nextNode() ) ) {
			if ( RX.test( n.nodeValue ) ) {
				nodes.push( n );
			}
			RX.lastIndex = 0;
		}
		for ( const t of nodes ) {
			t.nodeValue = t.nodeValue.replace( RX, resolvePlaceholder );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', () => replaceInTextNodes( document.body ) );
	} else {
		replaceInTextNodes( document.body );
	}
} )();
