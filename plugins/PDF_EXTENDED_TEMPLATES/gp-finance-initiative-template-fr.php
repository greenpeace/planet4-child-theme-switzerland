<?php
/**
* Template Name: Finance Initiative - FR
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

<?php
//Form Fields
$kanton = $form_data['field'][27] ?? '';
$politische_gemeinde = $form_data['field'][25] ?? '';
$plz = $form_data['field'][19]['zip'] ?? '';
$adresse = $form_data['field'][19]['street'] ?? '';
$geburtsdatum = $form_data['field'][24] ?? '';
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
	font-size: 22px;
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
h3 {
	font-size: 14.5px;
	line-height: 14.5px;
	font-weight: bold;
	color: #ffffff;
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
	color: #ffffff;
	position: absolute; 
	left:5mm; 
	top:5mm; 
	width:45%; 
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
	margin-top:-85px;
}
.main-cta {
	margin-top: -23px; 
	margin-left:10px;
}
.anschrift {
	position: absolute; 
	width: 200px; 
	height: 70px; 
	top:75mm; 
	right:30mm;
	font-weight: normal;
}
.info-text {
	background: #232D74;
	margin-top: 20px;
	padding-top: 20px;
	padding-left: 10px;
	padding-right: 10px;
	margin-bottom: 20px;
}
.description-text {

}
.additional-info {
	border-collapse: collapse;
	margin-bottom: 10px;
	margin-top: 10px;
	color: #ffffff;
}
.additional-info td {
	padding: 10px;
	vertical-align: middle;
	color: #ffffff;
	font-weight: bold;
}
.qr-code {
	width:60px;
}
.initiative-logo {
	margin-bottom:32px;
}
.frankatur {
	position: absolute; 
	top: 10mm; 
	left:127mm;
	width:330px;
}
/*-----------------------------------
Falzmarke
-------------------------------------*/
.fold {
	position: absolute;
	top: 145.5mm;
	left: 5mm;
	text-align: center;
	color: #a5a5a5;
	width: 200mm;
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
	vertical-align: top;
}
table.initiative-signatures .heading-unterzeichner .nr {
	vertical-align: middle;
}
table.initiative-signatures .angaben-unterzeichner td {
	height: 25px;
	vertical-align: middle;
} 
table.initiative-signatures td,
table.initiative-signatures th {
	padding-top: 3px;
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
	width: 820px;
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
/*---------
Section Code
----------*/
.section-code {
	position: absolute;
	bottom: 15mm;
	right: 5mm;
	rotate: -90;
}
</style>

<!--- Informationsteil obere Seitenhälfte --->
<div class="info-section font-inter">
	<h1 class="main-title">Pas d’argent pour <br/>la destruction de <br />l’environnement</h1>
	 	<img class="main-image" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-image-button-de.png"/>
	 	<h3 class="main-cta">Signez l’initiative maintenant !</h3>
	 	<div class="info-text">
		<p class="font-size-l description-text"><strong>La place financière suisse est un poids lourd à l’échelle mondiale. Les milliards gérés ou prêtés en Suisse causent de grands dégâts ailleurs, en finançant par exemple le déboisement de la forêt tropicale ou l’exploitation du charbon. Avec l’initiative sur la place financière, cet argent ne sera plus investi dans le réchauffement climatique ni la destruction de l’environnement.</strong></p>
	  <table class="additional-info">
	    <tr>
	      <td style="width: 20%"><img class="qr-code" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-qr-fr.png"/></td>
	      <td style="width: 80%"><p class="font-size-l">En savoir plus:<br /> www.initiative-place-financiere.ch</p></td>
	    </tr>
	  </table>
		</div>
	<img class="initiative-logo" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-footer-logo-fr.png"/>
</div>
<div class="frankatur">
	<img class="initiative-frankatur" src="<?php echo get_stylesheet_directory() ?>/plugins/PDF_EXTENDED_TEMPLATES/img/finance-initiative/finance-initiative-frankatur-fr.png"/>
</div>
<div class="anschrift font-inter">
	<p class="font-size-xl">Initiative sur la place financière<br/>Case postale 6094<br/>2500 Bienne 6</p>
</div>

<!--- Falzmarke Seitenmitte --->
	
<div class="fold font-inter">
	<table>
		<tr>
			<td style="border-bottom: 1px dotted #a5a5a5; text-align:center">
				<p class="font-size-xs fold-text"><em>Veuillez plier, scotcher et déposer dans la boîte aux lettres</em></p>
			</td>
		</tr>
	</table>
</div>

<!--- Offizieller Teil zur Inititative untere Seitenhälfte --->

<div class="initiative-official-section">
	<h2>Initiative populaire fédérale <strong>« Pour une place financière suisse durable et tournée vers lʼavenir (initiative sur la place financière) »</strong></h2>
	<p class="intro font-size-m">Publiée dans la Feuille fédérale <strong>26.11.2024</strong>. Les citoyennes et citoyens suisses soussignés ayant le droit de vote demandent, en vertu des articles 34, 136, 139 et 194 de la Constitution fédérale et conformément à la loi fédérale du 17 décembre 1976 sur les droits politiques (art. 68s.) :</p>
			<!--- Änderungen Bundesverfassung bzw. Initiativtext --->
	<table class="initiative-bv-content">
	  <tr>
	    <td style="vertical-align: top; width: 475px;">
	      <p class="font-size-s"><strong>La Constitution<sup>1</sup> est modifiée comme suit :</strong></p>
	      <p class="font-size-s"><strong><em>Art. 98a</em>&nbsp;&nbsp;&nbsp;Place financière durable</strong></p>
	      <p class="font-size-s"><sup>1</sup> La Confédération sʼengage en faveur dʼune orientation écologiquement durable de la place financière suisse. Elle prend des mesures pour aligner les flux financiers en conséquence ; ces mesures doivent être conformes aux normes internationales et aux obligations de la Suisse au titre du droit international en matière de compatibilité climatique et de protection et de reconstitution de la diversité biologique.</p>	
	      <p class="font-size-s"><sup>2</sup> Les participants suisses aux marchés financiers tels que les banques, les entreprises dʼassurance, les établissements financiers ainsi que les institutions de prévoyance et les institutions des assurances sociales alignent leurs activités commerciales ayant un impact sur lʼenvironnement à lʼétranger, notamment en raison dʼémissions de gaz à effet de serre, sur lʼobjectif de température convenu au niveau international en lʼétat actuel des connaissances scientifiques et sur les objectifs internationaux en matière de biodiversité ; ce faisant, ils tiennent compte des émissions directes et indirectes et des effets sur la biodiversité dans lʼensemble de la chaîne de création de valeur. La loi prévoit des exceptions pour les participants aux marchés financiers dont les activités ont un impact minime sur lʼenvironnement.</p>
	    </td>
	    <td style="vertical-align: top; width: 475px;">
	      <p class="font-size-s"><sup>3</sup> Les participants suisses aux marchés financiers ne fournissent pas de services de financement et dʼassurance servant à la mise en valeur ou à la promotion de nouveaux gisements dʼénergie fossile ainsi quʼà lʼexpansion de lʼexploitation de gisements dʼénergie fossile existants ; la loi fixe les restrictions correspondantes.</p>
	      <p class="font-size-s"> <sup>4</sup> Une surveillance est instaurée pour veiller à la mise en oeuvre de ces dispositions ; lʼautorité chargée de la surveillance est dotée de compétences en matière de décision et de sanction.</p>
	      <p class="font-size-xs">&nbsp;</p>
	      <p class="font-size-s"><strong><em>Art. 197 ch. 17&thinsp;</em><sup>2</sup></strong></p>
				<p class="font-size-s"><strong><em>17. Disposition transitoire ad art. 98a (Place financière durable)</em></strong></p>
				<p class="font-size-s">LʼAssemblée fédérale édicte les dispositions dʼexécution de lʼart. 98a au plus tard trois ans après lʼacceptation dudit article par le peuple et les cantons. Si les dispositions dʼexécution nʼentrent pas en vigueur dans ce délai, le Conseil fédéral les édicte sous la forme dʼune ordonnance et les met en vigueur dans un délai dʼun an. Lʼordonnance a effet jusquʼà lʼentrée en vigueur des dispositions dʼexécution édictées par lʼAssemblée fédérale.</p>
			</td>
	   </tr>
	  <tr>
	    <td class="fussnoten" colspan="2" style="vertical-align: middle;">
	      <hr>
	      <p class="font-size-s"><sup>1</sup> RS <strong>101</strong></p>
				<p class="font-size-s"><sup>2</sup> Le numéro définitif de la présente disposition transitoire sera fixé par la Chancellerie fédérale après le scrutin.</p>
	    </td>
	  </tr>
	</table>

	<p class="font-size-s">Seuls les électrices et électeurs ayant le droit de vote en matière fédérale dans la commune indiquée en tête de la liste peuvent y apposer leur signature. Les citoyennes et les citoyens qui appuient la demande doivent la signer de leur main. Celui qui se rend coupable de corruption active ou passive relativement à une récolte de signatures ou celui qui falsifie le résultat dʼune récolte de signatures effectuée à lʼappui dʼune initiative populaire est punissable selon lʼarticle 281 respectivement lʼarticle 282 du code pénal.</p>

	  <!--- Tabelle für Unterschriften --->
 	<table class="initiative-signatures">
	  <tbody>
	    <tr class="angaben-region">
			  <td colspan="2" class="plz border font-size-m"><span class="label">Canton : </span><?php echo esc_html( $kanton ); ?></td>
			  <td colspan="1" class="gemeinde border font-size-m"><span class="label">Nº postal : </span><?php echo esc_html( $plz ); ?></td>
			  <td colspan="3" class="kanton border font-size-m"><span class="label">Commune politique : </span><?php echo esc_html( $politische_gemeinde ); ?></td>
		  </tr>
		  <tr class="heading-unterzeichner">
			  <td class="nr leave-empty border"><span class="font-size-l leave-empty">Nr.</span></td>
			  <td class="name leave-empty border"><span class="label font-size-m leave-empty">Nom/Prénoms</span><br/><span class="font-size-s">(écrire de sa propre main et en majuscules)</span></td>
			 	<td class="geburtsdatum leave-empty border"><span class="label font-size-m leave-empty">Date de naissance</span><br/><span class="font-size-s">(jour/mois/année)</span></td>
			  <td class="adresse leave-empty border"><span class="label font-size-m leave-empty">Adresse exacte</span><br/><span class="font-size-s">(rue et numéro)</span></td>
			  <td class="unterschrift leave-empty border"><span class="label font-size-m leave-empty">Signature manuscrite</span></td>
			  <td class="kontrolle leave-empty border"><span class="label font-size-m leave-empty">Contrôle</span><br/><span class="font-size-s">(laisser en blanc)</span></td>
		  </tr> 	
	   	<tr class="angaben-unterzeichner">
			  <td style="width:60px;" class="nr border font-size-l">1.</td>
			  <td style="width:220px;" class="name border font-size-m"></td>
			  <td style="width:150px;" class="geburtsdatum-tag border font-size-m"><?php echo esc_html( $geburtsdatum ); ?></td>
			  <td style="width:220px;" class="adresse border font-size-m"><?php echo esc_html( $adresse ); ?></td>
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
			  <td style="width:58px;" class="nr border font-size-l">3.</td>
			  <td style="width:220px;" class="name border font-size-m"></td>
			  <td style="width:150px;" class="geburtsdatum-tag border font-size-m"></td>
			  <td style="width:220px;" class="adresse border font-size-m"></td>
			  <td style="width:220px" class="unterschrift border font-size-m"></td>
			  <td style="width:82px;" class="kontrolle border font-size-m leave-empty"></td>
		  </tr> 
	  </tbody>
	</table>

	   <!--- Initiativkomitee --->
	<p class="font-size-xs"><strong>Le comité d’initiative, composé des auteurs de celle-ci désignés ci-après, est autorisé à retirer la présente initiative populaire par une décision prise à la majorité absolue de ses membres ayant encore le droit de vote : </strong> <br/><strong>Gerhard Andrey</strong>, Chablioux-Parc 16, 1763 Granges-Paccot, <strong>Nicole Bardet</strong>, En Bouley 39, 1680 Romont, <strong>Samuel Bendahan</strong>, Route des Plaines-du-Loup 41, 1018 Lausanne, <strong>Kathrin Bertschy</strong>, Länggassstr. 10, 3012 Berne, <strong>Peter Bosshard</strong>, Feldgüetliweg 71, 8706 Meilen, <strong>Elgin Brunner</strong>, Zeunerstr. 17, 8037 Zurich, <strong>Sasha Cisar</strong>, Juliastr. 5, 8032 Zurich, <strong>Raphaël Comte</strong>, Postfach 76, 2035 Corcelles, <strong>Melanie Gajowski</strong>, Trittligasse 26, 8001 Zurich, <strong>Susanne Hochuli</strong>, Im Winkel 10, 5057 Reitnau, <strong>Marc Jost</strong>, Hohmadstr. 29, 3600 Thoune, <strong>Konrad Langhart</strong>, Breitenweg 1, 8477 Oberstammheim, <strong>Michaël Malquarti</strong>, Av. De Champel 59, 1206 Genève, <strong>Yvan Maillard Ardenti</strong>, Ch. des Cossettes 41, 1723 Marly, <strong>Lisa Mazzone</strong>, Av. Ernest-Pictet 5, 1203 Genève, <strong>Mattea Meyer</strong>, Unterrütiweg 3, 8400 Winterthour, <strong>Stefan Müller-Altermatt</strong>, Dorfstr. 6, 4715 Herbetswil, <strong>Jon Pult</strong>, Engadinstr. 19, 7000 Coire, <strong>Marc Rüdisüli</strong>, Hochwachtstr. 24, 8370 Sirnach, <strong>Barbara Schaffner</strong>, Riedstr. 4, 8112 Otelfingen, <strong>Frédéric Steimer</strong>, Av. Louis-Ruchonnet 24, 1003 Lausanne, <strong>Maya Tharian</strong>, Birkenstr. 44, 8107 Buchs, <strong>Thomas Vellacott</strong>, Gladiolenweg 3, 8048 Zurich, <strong>Natascha Wey</strong>, Waffenplatzstr. 95, 8002 Zurich, <strong>Céline Widmer</strong>, Anwandstr. 28, 8004 Zurich, <strong>Marc Wuarin</strong>, Ch. Du Pré-du-Couvent 3f, 1224 Chêne-Bougeries, <strong>Kurt Zaugg-Ott</strong>, Melchtalstr. 15, 3014 Berne</p>
	  <p class="font-size-s" style="margin-top:5px;">Expiration du délai imparti pour la récolte des signatures : <strong>26.05.2026</strong></p>
	  <p class="font-size-s" style="margin-top:5px;">Le/La fonctionnaire soussigné/e certifie que les _______ (nombre) signataires de lʼinitiative populaire dont les noms figurent ci-dessus ont le droit de vote en matière fédérale dans la commune susmentionnée et y exercent leurs droits politiques.</p>

	  <!--- Tabelle für Beglaubigung Amtsperson --->
	<table class="initiative-beglaubigung">
	  <tbody>
	  	<tr class="angaben-amtsperson">
	     	<td style="width:280px;" class="border font-size-s"><span>Lieu :</span></td>
	     	<td style="width:340px;" class="border font-size-s"><span>Signature manuscrite : </span></td>
	     	<td style="width:250px;" class="border font-size-s" rowspan="2">Sceau :</td>
	    </tr>
	    <tr class="angaben-amtsperson">
	     	<td class="border font-size-s"><span>Date : </span></td>
	     	<td class="border font-size-s"><span>Fonction officielle : </span></span></td>
	    </tr>
	  </tbody>
	</table>

	<!--- Abschlissende Info --->   
	<p class="font-size-s">Envoyez cette liste partiellement ou entièrement remplie le plus rapidement possible au comité dʼinitiative : Initiative sur la place financière, Boîte postale 6094, 2500 Bienne 6</p>
	<p class="font-size-s">Vous trouverez de plus amples informations et des feuilles de signatures sur : <strong><a class="font-size-s" href="https://initiative-place-financiere.ch">www.initiative-place-financiere.ch</a></strong></p>
</div>

<div class="section-code font-size-m font-inter">GP On</div>