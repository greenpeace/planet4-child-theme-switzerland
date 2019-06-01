jQuery(document).ready(function () {
	// Campaign Cover Block
	// Temporary Solution
	// See: https://tickets.greenpeace.ch/view.php?id=206
	var tagList = {
		"#Ernährung": "tag-ernaehrung",
		"#FinanzplatzSchweiz": "tag-finanzplatz-schweiz",
		"#Gletscher-Initiative": "tag-gletscher-initiative",
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

	jQuery('.campaign-thumbnail-block  .campaign-card-column').each(function () {
		campaignName = jQuery(this).find('.yellow-cta').text();

		if (campaignName in tagList) {
			jQuery(this).addClass(tagList[campaignName]);
		}
	});
});
