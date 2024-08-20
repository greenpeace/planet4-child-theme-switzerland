<?php
/**
* Template Name: Finance Initiative
* Version: 1.0
* Description: The template for the finance initiative 2024/25
* Author: Greenpeace Switzerland
* Author URI: https://www.greenpeace.ch
* Group: GreenpeaceCH
* License: GPLv2
* Required PDF Version: 4.0
* Tags: Initiative, Greenpeace, Finance
*/

/* Prevent direct access to the template (always good to include this) */
if ( ! class_exists( 'GFForms' ) ) {
return;
}

/**
 * All Gravity PDF v4/v5/v6 templates have access to the following variables:
 *
 * @var array  $form      The current Gravity Forms array
 * @var array  $entry     The raw entry data
 * @var array  $form_data The processed entry data stored in an array
 * @var object $settings  The current PDF configuration
 * @var array  $fields    An array of Gravity Forms fields which can be accessed with their ID number
 * @var array  $config    The initialised template config class – eg. /config/zadani.php
 */

?>

<style>
	body,@page {
		margin: 0;
		padding: 0;

	}
	.wrapper {
		position: relative;
		padding: 1cm;
		width: 210mm;
		height: 297mm;
		background-image: url("<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/bogen-initiative-100.png");
		background-size: contain;
	}
	.zip {
		position: absolute;
		top: 37.5mm;
		left: 17mm;
	}
	.city {
		position: absolute;
		top: 37.5mm;
		left: 85mm;
	}
	.canton {
		position: absolute;
		top: 37.5mm;
		left: 146mm;
	}
	.birthday {
		position: absolute;
		top: 55mm;
		left: 71mm;
	}
	.address {
		position: absolute;
		top: 55mm;
		left: 96mm;
	}
</style>


<div class="wrapper">

</div>

<?php
  // mPDF only supports position:absolute|fixed partially - as root elements i.e. it will not position blocks absolutely inside another block.
?>
<div class="zip">{address (ZIP / Postal Code):21.5}</div>
<div class="city">{address (City):21.3}</div>
<div class="canton">{address (State / Province):21.4}</div>
<div class="birthday">{birthday:26}</div>
<div class="address">{address (Street Address):21.1}</div>
