<?php
/**
* Template Name: Finance Initiative - DE
* Version: 1.0
* Description: tde template for tde finance initiative 2024/25
* Author: Greenpeace Switzerland
* Author URI: https://www.greenpeace.ch
* Group: GreenpeaceCH
* License: GPLv2
* Required PDF Version: 4.0
* Tags: Initiative, Greenpeace, Finance
*/

/* Prevent direct access to tde template (always good to include tdis) */
if ( ! class_exists( 'GFForms' ) ) {
return;
}

/**
 * All Gravity PDF v4/v5/v6 templates have access to tde following variables:
 *
 * @var array  $form      tde current Gravity Forms array
 * @var array  $entry     tde raw entry data
 * @var array  $form_data tde processed entry data stored in an array
 * @var object $settings  tde current PDF configuration
 * @var array  $fields    An array of Gravity Forms fields which can be accessed witd tdeir ID number
 * @var array  $config    tde initialised template config class – eg. /config/zadani.php
 */

?>

<style>
	body,@page {
		margin: 0mm;
		padding: 0mm;
		width: 210mm;
		height: 297mm;
	}
	body, @page {
	 	text-rendering: optimizeLegibility !important;
		-webkit-font-smoothing: antialiased !important;
		-moz-osx-font-smoothing: grayscale;
	}

/*--------------
General Page Styling
----------------*/
body,@page {
	font-family:  Inter, sans-serif;
}
.font-inter {
  font-family: Inter, sans-serif;
}
h1 {
	font-size: 24px;
	line-height: 24px;
	font-weight: bold;
	color: #ffffff;
	margin-bottom: 0px;
}
h2 {
	font-size: 13.5px;
	line-height: 16px;
	font-weight: 400;
	color: #155CA8;
	margin-bottom: 5px;
}
.font-size-xxl {
	font-size: 16px;
}
.font-size-xl {
	font-size: 13px;
}
.font-size-l {
	font-size: 12px;
}
.font-size-m {
	font-size: 10px;
}
.font-size-s {
	font-size: 9px;
}
.font-size-xs {
	font-size: 8px;
}
p {
	margin: 0px;
}
a {
	text-decoration: none;
}
hr {
	margin-top: 0px;
	margin-bottom: 3px;
}
/*---------
General table Styling 
----------*/
.leave-empty {
	background: #FDE8DF;
}
.label {
	font-weight: bold;
}

/*------------------------------------
Informationsteil - erste Seitenhälfte
--------------------------------------*/ 
.info-section {
	background: #232D74;
	color: #ffffff;
	padding-top: 15px;
	padding-right: 15px;
	padding-bottom: 0px;
	padding-left: 15px;
	position: absolute; 
	left:0; 
	top:0; 
	width:33%; 
	height:148mm;
}
.main-title {
	z-index: 1; 
	margin-top: 10px; 
	margin-right:10px; 
	margin-bottom:10px; 
	margin-left:10px
}
.main-image {
	z-index: -1; 
	margin-top:-60px;
}
.anschrift {
	position: absolute; 
	width: 200px; 
	height: 70px; 
	top:65mm; 
	right:60mm;
	font-weight: normal;
}
.description-text {
	margin-top: 10px;
	padding-left: 10px;
	padding-right: 10px;
	margin-bottom: 20px;
}
.additional-info {
	border-collapse: collapse;
	margin-bottom: 20px;
	margin-top: 25px;
	color: #ffffff;
}
.additional-info td {
	padding: 10px;
	vertical-align: bottom;
	color: #ffffff;
	font-weight: bold;
}
.qr-code {
	width:60px;
}
.initiative-logo {
	margin-left:-40px;
	margin-right:-22px;
	margin-bottom:-10px;
}
/*-----------------------------------
Falzmarke
-------------------------------------*/
.fold {
	position: absolute;
	top: 145.5mm;
	text-align: center;
	color: #a5a5a5;
}
.fold-text {
	color: #a5a5a5;
}
/*-----------------------------------
Initiativteil - zweite Seitenhälfte
-------------------------------------*/
.initiative-official-section {
	position:absolute; 
	top:145mm; 
	width:200mm; 
	padding:5mm;
	height: 140mm;
}
/*Tabelle Änderungen BV*/
table.initiative-bv-content {
	background: #ffffff;
	border-collapse: collapse;
	table-layout: fixed;
	width: 950px;
    border: 0.5px solid black;
    margin-top: 5px;
    margin-bottom: 5px;
}
table.initiative-bv-content td {
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 0px;
	padding-left: 5px;
}
table.initiative-bv-content .fussnoten {
	padding-bottom: 5px;
}
/* Tabelle für Unterschriften*/
table.initiative-signatures {
	background: #ffffff;
	border-collapse: collapse;
	table-layout: fixed;
	width: 950px;
	margin-top: 5px;
  margin-bottom: 5px;
}

table.initiative-signatures, 
table.initiative-signatures th, 
table.initiative-signatures td.border,{
  border: 0.5px solid black;

}
table.initiative-signatures, 
table.initiative-signatures th, 
table.initiative-signatures td {
  vertical-align: middle;
}
table.initiative-signatures .angaben-region td {
	height: 22px;
}
table.initiative-signatures .heading-unterzeichner td {
	height: 32px;
	vertical-align: middle;
}
table.initiative-signatures .angaben-unterzeichner td {
	height: 25px;
	vertical-align: middle;
} 
table.initiative-signatures td,
table.initiative-signatures th {
	padding-top: 2px;
	padding-right: 5px;
	padding-bottom: 2px;
	padding-left: 5px;
}
table.initiative-signatures .nr {
	text-align: center;
}

/*Tabelle Beglaubigung Amtsperson*/
table.initiative-beglaubigung {
	border-collapse: collapse;
	table-layout: fixed;
	width: 950px;
	margin-top: 5px;
	margin-bottom: 5px;
}
table.initiative-beglaubigung, 
table.initiative-beglaubigung th, 
table.initiative-beglaubigung td {
  vertical-align: top;
}
table.initiative-beglaubigung,
table.initiative-beglaubigung th,
table.initiative-beglaubigung td.border {
  border: 0.5px solid black;

}
table.initiative-beglaubigung td,
table.initiative-beglaubigung th {
	padding-top: 5px;
	padding-right: 5px;
	padding-bottom: 5px;
	padding-left: 5px;
}
table.initiative-beglaubigung .angaben-amtsperson td {
	height: 26px;
	vertical-align: top;
}
.underline {
	border-bottom: 1px solid #000000;
}
</style>

<!--- Informationsteil obere Seitenhälfte --->
<div class="info-section font-inter">
	<h1 class="main-title">Kein Geld <br/>für Zerstörung</h1>
	 	<img class="main-image" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-image-button-de.png"/>
		<p class="font-size-xl description-text"><strong>Der Schweizer Finanzplatz ist ein globales Schwergewicht. Die Milliarden, die hierzulande verwaltet oder als Kredite vergeben werden, richten woanders grossen Schaden an und fliessen beispielsweise in die Abholzung des Regenwalds oder den Abbau von Kohle. Mit der Finanzplatz-Initiative wird dieses Geld künftig nicht mehr in Klimaerhitzung und die Zerstörung der Umwelt investiert.</strong></p>
	  <table class="additional-info">
	    <tr>
	      <td><img class="qr-code" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-qr-de.png"/></td>
	      <td><p class="font-size-l">Mehr erfahren:<br /> www.finanzplatz-initiative.ch</p></td>
	    </tr>
	  </table>
	<img class="initiative-logo" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-logo-de.png"/>
</div>
<div class="frankatur" style="position: absolute; top: 10mm; left:125mm;width:330px;">
	<img class="initiative-frankatur" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-frankatur-de.png"/>
</div>
<div class="anschrift font-inter">
	<p class="font-size-xxl">Finanzplatz-Initiative<br/>Postfach 6094<br/>2500 Biel 6</p>
</div>

<!--- Falzmarke Seitenmitte --->
	
<div class="fold font-inter">
	<table>
		<tr>
			<td style="border-bottom: 1px dotted #a5a5a5; text-align:center">
				<p class="font-size-xs fold-text"><em>Bitte falten, zusammenkleben und in den Postbriefkasten werfen.</em></p>
			</td>
		</tr>
	</table>
</div>

<!--- Offizieller Teil zur Inititative untere Seitenhälfte --->

<div class="initiative-official-section">
	<h2>Eidgenössische Volksinitiative <strong>«Für einen nachhaltigen und zukunftsgerichteten Finanzplatz Schweiz (Finanzplatz-Initiative)»</strong></h2>
	<p class="intro font-size-m">Im Bundesblatt veröffentlicht am 26.11.2024. Die unterzeichnenden stimmberechtigten Schweizer Bürgerinnen und Bürger stellen hiermit, gestützt auf Art. 34, 136, 139 und 194 der Bundesverfassung und nach dem Bundesgesetz vom 17. Dezember 1976 über die politischen Rechte, Art. 68ff., folgendes Begehren:</p>
			<!--- Änderungen Bundesverfassung bzw. Initiativtext --->
	<table class="initiative-bv-content">
	  <tr>
	    <td style="vertical-align: top; width: 475px;">
	      <p class="font-size-s"><strong>Die Bundesverfassung<sup>1</sup> wird wie folgt geändert:</strong></p>
	      <p class="font-size-s"><strong>Art. 98a &nbsp;&nbsp;&nbsp;Nachhaltiger Finanzplatz</strong></p>
	      <p class="font-size-s"><sup>1</sup> Der Bund setzt sich für eine ökologisch nachhaltige Ausrichtung des Schweizer Finanzplatzes ein. Er trifft Massnahmen zur entsprechenden Ausrichtung der Finanzmittelflüsse; die Massnahmen müssen im Einklang stehen mit den internationalen Standards und völkerrechtlichen Verpflichtungen der Schweiz zur Klimaverträglichkeit und zum Schutz und zur Wiederherstellung der biologischen Vielfalt.</p>	
	      <p class="font-size-s"><sup>2</sup> Schweizer Finanzmarktteilnehmende wie Banken, Versicherungsunternehmen, Finanzinstitute sowie Vorsorge- und Sozialversicherungseinrichtungen richten ihre Geschäftstätigkeiten mit Umweltauswirkungen im Ausland, insbesondere aufgrund von Treibhausgasemissionen, auf das nach dem aktuellen Stand der Wissenschaft international vereinbarte Temperaturziel und auf die internationalen Biodiversitätsziele aus; dabei berücksichtigen sie direkte und indirekte Emissionen sowie die Auswirkungen auf die Biodiversität entlang der gesamten Wertschöpfungskette. Das Gesetz sieht Ausnahmen vor für Finanzmarktteilnehmende, deren Tätigkeiten mit geringen Umweltauswirkungen verbunden sind.</p>
	    </td>
	    <td style="vertical-align: top; width: 475px;">
	      <p class="font-size-s"><sup>3</sup> Schweizer Finanzmarktteilnehmende erbringen keine Finanzierungs- und Versicherungsdienstleistungen, die der Erschliessung und der Förderung neuer sowie der Ausweitung des Abbaus bestehender fossiler Energievorkommen dienen; das Gesetz legt die entsprechenden Einschränkungen fest.</p>
	      <p class="font-size-s"> <sup>4</sup> Zur Durchsetzung dieser Vorgaben wird eine Aufsicht vorgesehen; diese hat Verfügungs- und Sanktionskompetenzen.</p>
	      <p class="font-size-xs">&nbsp;</p>
	      <p class="font-size-s"><strong><em>Art. 197 Ziff. 17</em><sup>2</sup></strong></p>
				<p class="font-size-s"><strong><em>17. Übergangsbestimmung zu Art. 98a (Nachhaltiger Finanzplatz)</em></strong></p>
				<p class="font-size-s">Die Bundesversammlung erlässt die Ausführungsbestimmungen zu Artikel 98a spätestens drei Jahre nach dessen Annahme durch Volk und Stände. Treten die Ausführungsbestimmungen innerhalb dieser Frist nicht in Kraft, so erlässt der Bundesrat die Ausführungsbestimmungen in Form einer Verordnung und setzt sie innerhalb eines Jahres in Kraft. Die Verordnung gilt bis zum Inkrafttreten der von der Bundesversammlung erlassenen Ausführungsbestimmungen.</p>
			</td>
	   </tr>
	  <tr>
	    <td class="fussnoten" colspan="2" style="vertical-align: middle;">
	      <hr>
	      <p class="font-size-s"><sup>1</sup> SR <strong>101</strong></p>
				<p class="font-size-s"><sup>2</sup> Die endgültige Ziffer dieser Übergangsbestimmung wird nach der Volksabstimmung von der Bundeskanzlei festgelegt.</p>
	    </td>
	  </tr>
	</table>

	<p class="font-size-s">Auf dieser Liste können nur Stimmberechtigte unterzeichnen, die in der genannten politischen Gemeinde in eidgenössischen Angelegenheiten stimmberechtigt sind. Bürgerinnen und Bürger, die das Begehren unterstützen, mögen es handschriftlich unterzeichnen. Wer bei einer Unterschriftensammlung besticht oder sich bestechen lässt oder wer das Ergebnis einer Unterschriftensammlung für eine Volksinitiative fälscht, macht sich strafbar nach Art. 281 beziehungsweise nach Art. 282 des Strafgesetzbuches.</p>

	  <!--- Tabelle für Unterschriften --->
 	<table class="initiative-signatures">
	  <tbody>
	    <tr class="angaben-region">
			  <td colspan="2" class="plz border font-size-m"><span class="label">Kanton: </span>{Kanton:36}</td>
			  <td colspan="1" class="gemeinde border font-size-m"><span class="label">PLZ: </span>{address (ZIP / Postal Code):21.5}</td>
			  <td colspan="3" class="kanton border font-size-m"><span class="label">Politische Gemeinde: </span>{Politische Gemeinde:32}</td>
		  </tr>
		  <tr class="heading-unterzeichner">
			  <td class="nr leave-empty border"><span class="font-size-l leave-empty">Nr.</span></td>
			  <td class="name leave-empty border"><span class="label font-size-m leave-empty">Name/Vornamen</span><br/><span class="font-size-s">(eigenhändig in Blockschrift)</span></td>
			 	<td class="geburtsdatum leave-empty border"><span class="label font-size-m leave-empty">Geburtsdatum</span><br/><span class="font-size-s">(Tag/Monat/Jahr)</span></td>
			  <td class="adresse leave-empty border"><span class="label font-size-m leave-empty">Wohnadresse</span><br/><span class="font-size-s">(Strasse und Hausnummer)</span></td>
			  <td class="unterschrift leave-empty border"><span class="label font-size-m leave-empty">Eigenhändige Unterschrift</span></td>
			  <td class="kontrolle leave-empty border"><span class="label font-size-m leave-empty">Kontrolle</span><br/><span class="font-size-s">(leer lassen)</span></td>
		  </tr> 	
	   	<tr class="angaben-unterzeichner">
			  <td style="width:60px;" class="nr border font-size-l">1.</td>
			  <td style="width:220px;" class="name border font-size-m"></td>
			  <td style="width:150px;" class="geburtsdatum-tag border font-size-m">{Geburtsdatum:26}</td>
			  <td style="width:220px;" class="adresse border font-size-m">{address (Street Address):21.1}</td>
			  <td style="width:220px" class="unterschrift border font-size-m"></td>
			  <td style="width:80px;" class="kontrolle border font-size-m leave-empty"></td>
		  </tr>
		  <tr class="angaben-unterzeichner">
			  <td style="width:60px;" class="nr border font-size-l">2.</td>
			  <td style="width:220px;" class="name border font-size-m"></td>
			  <td style="width:150px;" class="geburtsdatum-tag border font-size-m"></td>
			  <td style="width:220px;" class="adresse border font-size-m"></td>
			  <td style="width:220px" class="unterschrift border font-size-m"></td>
			  <td style="width:80px;" class="kontrolle border font-size-m leave-empty"></td>
		  </tr>
		  <tr class="angaben-unterzeichner">
			  <td style="width:60px;" class="nr border font-size-l">3.</td>
			  <td style="width:220px;" class="name border font-size-m"></td>
			  <td style="width:150px;" class="geburtsdatum-tag border font-size-m"></td>
			  <td style="width:220px;" class="adresse border font-size-m"></td>
			  <td style="width:220px" class="unterschrift border font-size-m"></td>
			  <td style="width:80px;" class="kontrolle border font-size-m leave-empty"></td>
		  </tr> 
	  </tbody>
	</table>

	   <!--- Initiativkomitee --->
	<p class="font-size-xs"><strong>Das Initiativkomitee, bestehend aus nachstehenden Urheberinnen und Urhebern, ist berechtigt, diese Volksinitiative mit absoluter Mehrheit seiner noch stimmberechtigten Mitglieder zurückzuziehen:</strong> <br/><strong>Gerhard Andrey</strong>, Chablioux-Parc 16, 1763 Granges-Paccot, <strong>Nicole Bardet</strong>, En Bouley 39, 1680 Romont, <strong>Samuel Bendahan</strong>, Route des Plaines-du-Loup 41, 1018 Lausanne, <strong>Kathrin Bertschy</strong>, Länggassstr. 10, 3012 Bern, <strong>Peter Bosshard</strong>, Feldgüetliweg 71, 8706 Meilen, <strong>Elgin Brunner</strong>, Zeunerstr. 17, 8037 Zürich, <strong>Sasha Cisar</strong>, Juliastr. 5, 8032 Zürich, <strong>Raphaël Comte</strong>, Postfach 76, 2035 Corcelles, <strong>Melanie Gajowski</strong>, Trittligasse 26, 8001 Zürich, <strong>Susanne Hochuli</strong>, Im Winkel 10, 5057 Reitnau, <strong>Marc Jost</strong>, Hohmadstr. 29, 3600 Thun, <strong>Konrad Langhart</strong>, Breitenweg 1, 8477 Oberstammheim, <strong>Michaël Malquarti</strong>, Av. De Champel 59, 1206 Genf, <strong>Yvan Maillard Ardenti</strong>, Ch. des Cossettes 41, 1723 Marly, <strong>Lisa Mazzone</strong>, Av. Ernest-Pictet 5, 1203 Genf, <strong>Mattea Meyer</strong>, Unterrütiweg 3, 8400 Winterthur, <strong>Stefan Müller-Altermatt</strong>, Dorfstr. 6, 4715 Herbetswil, <strong>Jon Pult</strong>, Engadinstr. 19, 7000 Chur, <strong>Marc Rüdisüli</strong>, Hochwachtstr. 24, 8370 Sirnach, <strong>Barbara Schaffner</strong>, Riedstr. 4, 8112 Otelfingen, <strong>Frédéric Steimer</strong>, Av. Louis-Ruchonnet 24, 1003 Lausanne, <strong>Maya Tharian</strong>, Birkenstr. 44, 8107 Buchs, <strong>Thomas Vellacott</strong>, Gladiolenweg 3, 8048 Zürich, <strong>Natascha Wey</strong>, Waffenplatzstr. 95, 8002 Zürich, <strong>Céline Widmer</strong>, Anwandstr. 28, 8004 Zürich, <strong>Marc Wuarin</strong>, Ch. Du Pré-du-Couvent 3f, 1224 Chêne-Bougeries, <strong>Kurt Zaugg-Ott</strong>, Melchtalstr. 15, 3014 Bern</p>
	  <p class="font-size-s" style="margin-top:5px;">Ablauf der Sammelfrist: <strong>26.05.2026</strong></p>
	  <p class="font-size-s" style="margin-top:5px;">Die unterzeichnende Amtsperson bescheinigt hiermit, dass obenstehende _______ (Anzahl) Unterzeichnende in eidgenössischen Angelegenheiten stimmberechtigt sind und ihre politischen Rechte in der erwähnten Gemeinde ausüben.</p>

	  <!--- Tabelle für Beglaubigung Amtsperson --->
	<table class="initiative-beglaubigung">
	  <tbody>
	  	<tr class="angaben-amtsperson">
	     	<td style="width:350px;" class="border font-size-s"><span>Ort:</span></td>
	     	<td style="width:350px;" class="border font-size-s"><span>Eigenhändige Unterschrift: </span></td>
	     	<td style="width:250px;" class="border font-size-s" rowspan="2">Amtsstempel:</td>
	    </tr>
	    <tr class="angaben-amtsperson">
	     	<td class="border font-size-s"><span>Datum: </span></td>
	     	<td class="border font-size-s"><span>Amtliche Eigenschaft: </span></span></td>
	    </tr>
	  </tbody>
	</table>

	<!--- Abschlissende Info --->   
	<p class="font-size-s">Senden Sie diese Liste teilweise oder vollständig ausgefüllt möglichst bald an das Initiativkomitee: Finanzplatz-Initiative, Postfach 6094, 2500 Biel 6</p>
	<p class="font-size-s">Weitere Informationen und Unterschriftenbögen finden Sie unter: <strong><a class="font-size-s" href="https://finanzplatz-initiative.ch">www.finanzplatz-initiative.ch</a></strong></p>
</div>