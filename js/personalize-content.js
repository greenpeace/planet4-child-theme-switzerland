/**
 * Replace placeholders in page content with URL parameters.
 * Expects placeholders like {{firstName|Freund}} ({{parameter|fallback}})
 */
(function () {
	const params = new URLSearchParams(window.location.search);

	// Regex for {{paramName|Fallback}}, fallback is optional
	const RX = /\{\{\s*([a-zA-Z0-9_-]+)(?:\|([^}]*))?\s*\}\}/g;

	function escapeHTML(input) {
		return input
			.replace(/&/g, "&amp;")
			.replace(/</g, "&lt;")
			.replace(/>/g, "&gt;")
			.replace(/"/g, "&quot;")
			.replace(/'/g, "&#39;");
	}

	function resolvePlaceholder(_, name, fallback) {
		let val = params.get(name);

		// Empty strings or spaces are treated as not set
		if (val && val.trim() !== "") {
			// Clean text (prevent code from getting inserted)
			return escapeHTML(val.replace(/[<>\u0000-\u001F]/g, ""));
		}

		return (fallback !== undefined ? fallback : "").trim();
	}

	function replaceInTextNodes(root) {
		// Search text nodes
		const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
		const todo = [];
		let node;
		while ((node = walker.nextNode())) {
			if (RX.test(node.nodeValue)) {
				todo.push(node);
			}
			RX.lastIndex = 0; // Reset für nächsten Check
		}
		// Replace
		for (const n of todo) {
			n.nodeValue = n.nodeValue.replace(RX, resolvePlaceholder);
		}
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", () => replaceInTextNodes(document.body));
	} else {
		replaceInTextNodes(document.body);
	}
})();
