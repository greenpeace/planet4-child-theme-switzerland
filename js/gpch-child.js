jQuery(document).ready(function () {
	// Campaign Cover Block
	// Temporary Solution
	// See: https://tickets.greenpeace.ch/view.php?id=206
	var tagList = {
		'#Klima': 'tag-klima',
		'#Climat': 'tag-climat',
		'#Ernährung': 'tag-ernaehrung',
		'#Nutrition': 'tag-nutrition',
		'#FinanzplatzSchweiz': 'tag-finanzplatz-schweiz',
		'#PlaceFinanciere': 'tag-place-financiere',
		'#GletscherInitiative': 'tag-gletscher-initiative',
		'#InitiativeGlaciers': 'tag-initiative-glaciers',
		'#Klimabewegung': 'tag-klimabewegung',
		'#MouvementClimatique': 'tag-mouvement-climatique',
		'#Klimagerechtigkeit': 'tag-klimagerechtigkeit',
		'#JusticeClimatique': 'tag-justice-climatique',
		'#Verpackung': 'tag-verpackung',
		'#Emballage': 'tag-emballage',
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
	}

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
