jQuery(document).ready(function () {
	// Campaign Cover Block
	// Temporary Solution
	// See: https://tickets.greenpeace.ch/view.php?id=206
	var tagList = {
		'#Klima': 'tag-klima',
		'#Climat': 'tag-climat',
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
	jQuery('.campaign-covers-block  .campaign-card-column').each(function () {
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
		campaignName = jQuery(this).find('.cover-card-tag').first().text().trim();

		if (campaignName in tagList) {
			jQuery(this).addClass(tagList[campaignName]);
		}
	});
	
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
