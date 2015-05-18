<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require(__DIR__.'/api/API.php');

$api = new \loci\api\API('9097b7c946794s856508f05e3506d9bfb');
$api->isDev(true);

?>
	<h1>API-Test</h1>
	<table>
		<tr>
			<td>API-Token</td>
			<td>9405205982830810d21939b2a3f0ec3e</td>
		</tr>
	</table>
	<a href="#Mandant">Mandant</a><br/>
	<a href="#Kunde">Kunde</a><br/>
	<a href="#Partner">Partner</a><br/>
	<a href="#Kampagne">Kampagne</a><br/>
	<a href="#Aktion">Aktion</a><br/>
	<a href="#Teilnehmer">Teilnehmer</a><br/>
	<a href="#TeilnehmerUpdate">TeilnehmerUpdate</a><br/>
	<a href="#SetAktionsDaten">SetAktionsDaten</a><br/>
	<a href="#GetAktionsDaten">GetAktionsDaten</a><br/>
	<br/>
	<h2 id="Mandant">Mandant</h2>
<?php
echo '<pre>';
print_r($api->getMandant());
echo '</pre>';
?>
	<h2 id="Kunde">Kunde</h2>
<?php
echo '<pre>';
print_r($api->getKunde(1));
echo '</pre>';
?>
	<h2 id="Partner">Partner</h2>
<?php
echo '<pre>';
print_r($api->getPartner(1));
echo '</pre>';
?>
	<h2 id="Kampagne">Kampagne</h2>
<?php
echo '<pre>';
print_r($api->getKampagne(1));
echo '</pre>';
?>
	<h2 id="Aktion">Aktion</h2>
<?php
echo '<pre>';
$aktion = $api->getAktion(17);
print_r($aktion);
echo '</pre>';
?>
	<h2 id="Teilnehmer">Teilnehmer</h2>
<?php
echo '<pre>';
$teilnehmer = $api->getTeilnehmer(['email' => 'ch@allatnet.de']);
print_r($teilnehmer);
//print_r($api->getTeilnehmer(['_idInternal'=>['idAction'=>21]]));
//print_r($api->getTeilnehmer(['_id'=>'553958b71f03ad08238b4567']));
echo '</pre>';
$time = time();
?>
	<h2 id="TeilnehmerUpdate">TeilnehmerUpdate</h2>
	Setze das time Attribut auf <?= $time ?>!
<?php
$teilnehmer->time = $time;
echo '<pre>';
print_r($api->updateTeilnehmer($teilnehmer));
echo '</pre>';
?>

	<h2 id="GetAktionsDaten">GetAktionsDaten</h2>
<?php
echo '<pre>';
print_r($api->getAktionsDaten($teilnehmer, $aktion->id));
echo '</pre>';
?>
	<h2 id="SetAktionsDaten">SetAktionsDaten</h2>
Setze das zeit Attribut auf <?= $time ?>!
<?php
echo '<pre>';
print_r($api->setAktionsDaten($teilnehmer, $aktion->id, ['testaktion' => 'testwert', 'zeit' => $time]));
echo '</pre>';
?>