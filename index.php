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
$mandant = $api->getMandant();
if($mandant){
	echo '<pre>';
	print_r($mandant);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}
?>
	<h2 id="Kunde">Kunde</h2>
<?php
$kunde = $api->getKunde(1);
if($kunde){
	echo '<pre>';
	print_r($kunde);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}
?>
	<h2 id="Partner">Partner</h2>
<?php
$partner = $api->getPartner(1);
if($partner){
	echo '<pre>';
	print_r($partner);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}
?>
	<h2 id="Kampagne">Kampagne</h2>
<?php
$kampagne = $api->getKampagne(1);
if($kampagne){
	echo '<pre>';
	print_r($kampagne);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}
?>
	<h2 id="Aktion">Aktion</h2>
<?php
$aktion = $api->getAktion(17);
if($aktion){
	echo '<pre>';
	print_r($aktion);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}
?>
	<h2 id="Teilnehmer">Teilnehmer</h2>
<?php
$teilnehmer = $api->getTeilnehmer(['email' => 'ch@allatnet.de']);
//$teilnehmer = $api->getTeilnehmer(['_idInternal'=>['idAction'=>21]]);
//$teilnehmer = $api->getTeilnehmer(['_id'=>'553958b71f03ad08238b4567']);
if($teilnehmer){
	echo '<pre>';
	print_r($teilnehmer);
	echo '</pre>';
}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}


$time = time();
if($teilnehmer){
	?>
	<h2 id="TeilnehmerUpdate">TeilnehmerUpdate</h2>
	Setze das time Attribut auf <?= $time ?>!
	<?php
	$teilnehmer->time = $time;
	$update = $api->updateTeilnehmer($teilnehmer);
	if($update){
		echo '<pre>';
		print_r($update);
		echo '</pre>';
	}else{
		echo '<pre>';
		print_r($api->getError());
		echo '</pre>';
	}
}


if($teilnehmer && $aktion){
	?>
	<h2 id="GetAktionsDaten">GetAktionsDaten</h2>
	<?php
	$getAktionsDaten = $api->getAktionsDaten($teilnehmer, $aktion->id);
	if($getAktionsDaten){
		echo '<pre>';
		print_r($getAktionsDaten);
		echo '</pre>';
	}else{
		echo '<pre>';
		print_r($api->getError());
		echo '</pre>';
	}
}


if($teilnehmer && $aktion){
	?>
	<h2 id="SetAktionsDaten">SetAktionsDaten</h2>
	Setze das zeit Attribut auf <?= $time ?>!
	<?php
	$setAktionsDaten = $api->setAktionsDaten($teilnehmer, $aktion->id, ['testaktion'=>'testwert', 'zeit'=>$time]);
	if($setAktionsDaten){
		echo '<pre>';
		print_r($setAktionsDaten);
		echo '</pre>';
	}else{
		echo '<pre>';
		print_r($api->getError());
		echo '</pre>';
	}
}
?>