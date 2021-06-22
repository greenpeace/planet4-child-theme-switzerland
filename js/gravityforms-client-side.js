const gpchGFClientside = {
	populateFields: () => {
		if( typeof gpchGfClientSideConfig !== 'undefined' ) {
			const urlParams = new URLSearchParams( window.location.search );
			
			gpchGfClientSideConfig.populate.forEach( ( field ) => {
				const value = urlParams.get( field.parameter );
				
				if( value !== null ) {
					const inputField = document.getElementById(
					  field.fieldId ).value = value;
				}
			} )
		}
	},
}

gpchGFClientside.populateFields()
