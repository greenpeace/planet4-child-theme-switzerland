jQuery(document).ready(function () {
	// Campaign Cover Block
	// Temporary Solution
	// See: https://tickets.greenpeace.ch/view.php?id=206
	var tagList = {
		"#Klima": "tag-klima",
		"#Ernährung": "tag-ernaehrung",
		"#FinanzplatzSchweiz": "tag-finanzplatz-schweiz",
		"#GletscherInitiative": "tag-gletscher-initiative",
		"#Klimabewegung": "tag-klimabewegung",
		"#Klimagerechtigkeit": "tag-klimagerechtigkeit",
		"#Verpackung": "tag-verpackung",
		"#Antarktis": "tag-antarktis",
		"#Arktis": "tag-arktis",
		"#Chemie": "tag-chemie",
		"#Energie": "tag-energie",
		"#Landwirtschaft": "tag-landwirtschaft",
		"#Meer": "tag-meer",
		"#Wald": "tag-wald",
	};

	// Insert tag classes in campaign covers block
	jQuery('.campaign-thumbnail-block  .campaign-card-column').each(function () {
		campaignName = jQuery(this).find('.yellow-cta').text();

		if (campaignName in tagList) {
			jQuery(this).addClass(tagList[campaignName]);
		}
	});


	// Insert tag classes in archive and search result pages
	jQuery('body.archive .multiple-search-result .search-result-list-item, body.search .multiple-search-result .search-result-list-item').each(function () {
		campaignName = jQuery(this).find('.search-result-item-tag').first().text();

		if (campaignName in tagList) {
			jQuery(this).addClass(tagList[campaignName]);
		}
	});

	// Insert tag classes in action covers block
	jQuery('.covers-block .cover-card').each(function () {
		campaignName = jQuery(this).find('.cover-card-tag').first().text();

		if (campaignName in tagList) {
			jQuery(this).addClass(tagList[campaignName]);
		}
	});
});
