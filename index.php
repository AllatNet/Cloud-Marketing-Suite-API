<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require(__DIR__.'/api/API.php');

$api = new \loci\api\API('9097b7c946794856508f05e3506d9bfb');
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
	<a href="#TeilnehmerEmail">TeilnehmerEmail</a><br/>
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
$kunde = $api->getKunde($mandant->kunden[0]->id);
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
$partner = $api->getPartner($kunde->partner[0]->id);
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
$kampagne = $api->getKampagne($partner->kampagnen[0]->id);
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
$aktion = $api->getAktion($kampagne->aktionen[0]->id);
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
	<h2 id="Teilnehmer">Teilnehmer erstellen</h2>
<?php
$teilnehmer = new \loci\api\lib\Teilnehmer();
$teilnehmer->vorname = 'Tester';
$teilnehmer->nachname = 'Testers';
$teilnehmer->email = 'ch@allatnet.de';
$teilnehmerCreate = $api->createTeilnehmer($kampagne->aktionen[0]->id, $teilnehmer);
if($teilnehmerCreate){
	echo '<pre>';
	print_r($teilnehmerCreate);
	echo '</pre>';

}else{
	echo '<pre>';
	print_r($api->getError());
	echo '</pre>';
}

?>
	<h2>Teilnehmer</h2>
<?php
$teilnehmer = $api->getTeilnehmer(['idAktion'=>$kampagne->aktionen[0]->id, 'email' => 'ch@allatnet.de']);

//Teilnehmer mit Hash finden
//$teilnehmer = $api->getTeilnehmer(['idAktion'=>$kampagne->aktionen[0]->id, '_id'=>'553958b71f03ad08238b4567']);

//Teilnehmer ID auslesen:
//$teilnehmer->id;

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
//	$update = $api->updateTeilnehmer($teilnehmer);
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
//	$setAktionsDaten = $api->setAktionsDaten($teilnehmer, $aktion->id, ['testaktion'=>'testwert', 'zeit'=>$time]);
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

<?php
if($teilnehmer){
	?>
	<h2 id="TeilnehmerEmail">TeilnehmerEmail</h2>
	<?php
	$from = "Loci <info@loci.biz>";
	$subject = "Test E-Mail";
	$text = "Hallo ~vorname~ ~nachname~ ~name~,

	hier spricht deine Cloud-Marketing-Suite
	<b>HTML</b>

	LG Tester";
	?>
	Sende dem Teilnehmer von "<?= $from ?>" eine Nachricht<br />
	Betreff: "<?= $subject ?><br />
	Text: "<?= nl2br($text) ?>
	<?php
//	$sendMail = $api->sendMail($teilnehmer, $aktion->id, [
//		'from'=>$from,
//		'to'=>$teilnehmer->email,
//		'subject'=>$subject,
//		'text'=>$text,
//        'html'=>true,
//	]);
//	if($sendMail){
//		echo '<pre>';
//		echo 'Erfolgreich';
//		echo '</pre>';
//	}else{
//		echo '<pre>';
//		echo 'Fehler';
//		echo '</pre>';
//	}
}


?>

<h1>Geänderte Teilnehmer-Hashes</h1>
<?php

$config = [
	'idAktion'=>$aktion->id,
	'from'=>'1422748799',
];

$hashes = $api->getTeilnehmerChanged($config);
echo '<pre>';
print_r($api->getError());
echo '</pre>';

echo '<pre>';
print_r($hashes);
echo '</pre>';


?>


<h1>Teilnehmer-Stammdaten</h1>
<?php

//$teilnehmer = $api->getTeilnehmerStammdaten(['idAktion'=>104, 'email' => 'ch@allatnet.de']);
$teilnehmer = $api->getTeilnehmerStammdaten(['idAktion'=>104, '_id' => '55b0b69c1f03ad06608b4567']);
echo '<pre>';
print_r($teilnehmer);
echo '</pre>';

?>

<h1>Teilnehmer-Löschen</h1>
<?php

//$teilnehmer = $api->getTeilnehmerStammdaten(['idAktion'=>104, 'email' => 'ch@allatnet.de']);
$teilnehmer = $api->deleteTeilnehmer(['idAktion'=>21, '_id' => '55b0b69c1f03ad06608b4567']);
echo '<pre>';
print_r($teilnehmer);
echo '</pre>';

?>
