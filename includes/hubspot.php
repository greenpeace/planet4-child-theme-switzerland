<?php

add_filter( 'gform_hubspot_output_tracking_script', '__return_false' );


add_filter( 'gform_hubspot_form_object_pre_save_feed', 'gpchchild_gf_hb_form_object_pre_save_feed', 10, 1 );

/**
 * Changes to the lifecycle stage in forms
 * Changing how the lifecycle stage for CRM records is handled in forms.
 * Instead of being a form field, the lifecycle stage will be included in the
 * form settings in a new selectedExternalOptions field.
 * More info: https://developers.hubspot.com/changelog/changes-to-the-lifecycle-stage-in-forms
 *
 * phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
 *
 * @param mixed $hs_form hubspot form.
 *
 * @return mixed $hs_form hubspot form.
 */
function gpchchild_gf_hb_form_object_pre_save_feed( $hs_form ) {
	if ( ! empty( $hs_form['selectedExternalOptions'] ) ) {
		$lifecyclestage_id = '';
		foreach ( $hs_form['selectedExternalOptions'] as $key => $external_option ) {
			if ( rgar( $external_option, 'propertyName' ) !== 'lifecyclestage' ) {
				continue;
			}

			if ( rgar( $external_option, 'objectTypeId' ) === '0-1' ) {
				$lifecyclestage_id = rgar( $external_option, 'id' );
				continue;
			}

			if ( rgar( $external_option, 'objectTypeId' ) === '0-2' ) {
				$lifecyclestage_id = '';
				break;
			}
		}

		if ( ! empty( $lifecyclestage_id ) ) {
			$hs_form['selectedExternalOptions'][] = array(
				'referenceType' => 'PIPELINE_STAGE',
				'objectTypeId'  => '0-2',
				'propertyName'  => 'lifecyclestage',
				'id'            => $lifecyclestage_id,
			);
		}
	}

	return $hs_form;
}
