<?php

// Variablen
use loci\api\API;

$home        = __DIR__.'/';
$actual_link = $_SERVER["REQUEST_URI"];

// Fehleranzeige
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL || ~E_NOTICE || ~E_WARNING);

// API Bibliothek
require($home.'api/API.php');
?>

<html>
<head>
	<title>API-Test</title>
	<style>
		h2.pointer {
			cursor: pointer;
		}
		h2.pointer:after {
			content: '\25BC';
			display: inline;
			position: absolute;
			font-size: 14px;
			top: 50%;
			margin-left: 10px;
		}
	</style>
	<script
			src="https://code.jquery.com/jquery-3.6.0.min.js"
			integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
			crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript">
		function toggleApi(id) {
			var element = $(id);
			console.log(element.css('display'));
			if (element.css('display') == 'none') {
				element.show(250);
			} else {
				element.hide(250);
			}

		}
	</script>
</head>
<body>
<div class="container" style="width: 1000px; margin: 0 auto;">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<h1>API-Test</h1>
					<table class="table table-bordered">
						<tr>
							<td>API-Token</td>
							<?php
							$value = "";
							if (isset($_GET["t"]) && !empty($_GET["t"]))
								$value = ' value="'.$_GET["t"].'"';
							?>
							<td>
								<form action="index.php"><input style="width:100%;" type="text" name="t"
																placeholder="098f6bcd4621d373cade4e832627b4f6" <?= $value ?>/>
								</form>
								<p>Enter drücken um den Token zu verwenden</p></td>
						</tr>
					</table>
				</div>
			</div>

			<?php

			/**
			 * Token
			 */
			if (isset($_GET["t"]) && !empty($_GET["t"])):

				$api = new API($_GET["t"]);
				$api->isDev(true);
				$t = $api->startTest();

				$mandant = $api->getMandant();
				?>
				<div class="row">
					<div class="col-md-1">
						<h2>
							<?php
							$mandantenFirma = $mandant->firma;
							if ($mandant instanceof \loci\api\lib\Mandant && !empty($mandantenFirma))
								echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
							else
								echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
							?>
						</h2>
					</div>
					<div class="col-md-11">
						<h2 class="pointer" onclick="toggleApi('#mandant')">Mandant</h2>
					</div>
					<div class="col-md-12" id="mandant" style="display: none;">
						<?php
						if ($mandant) {
							echo '<pre>';
							print_r($mandant);
							echo '</pre>';
						} else {
							echo '<h4>Errors:</h4>'."\r\n";
							echo '<pre>';
							print_r($api->getError());
							echo '</pre>';
						}
						?>
					</div>
				</div>

				<?php
				/**
				 * Kunden
				 */
				if (!$t::isEmpty($mandant->kunden[0]->id)) {
					($t::_g('k')) ? $idKunde = $t::_g('k') : $idKunde = $mandant->kunden[0]->id;
					$kunde = $api->getKunde($idKunde);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								$kundenFirma1 = $kunde->firma1;
								if ($kunde instanceof \loci\api\lib\Kunde && !empty($kundenFirma1))
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#kunde')">Kunde</h2>
						</div>
						<div class="col-md-12">
							<?php
							$action = "kunde";
							$form   = '<form action="'.$actual_link.'" method="get">';
							$form   .= '	<label for="switch_'.$action.'">Ändern</label>';
							$form   .= '	<select id="switch_'.$action.'" name="k">';
							if (is_array($mandant->kunden)) {
								foreach ($mandant->kunden as $xkunde) {
									($xkunde->id == $idKunde) ? $extra = 'selected' : $extra = '';
									$form .= '<option value="'.$xkunde->id.'" '.$extra.'>'.$xkunde->firma1.' (#'.$xkunde->id.')</option>';
								}
							}
							$form .= '	</select>';
							$form .= '<input type="hidden" name="t" value="'.$t::_g('t').'">';
							$form .= '</form>';
							echo $form;
							?>
						</div>
						<div class="col-md-12" id="kunde" style="display: none;">
							<?php
							if ($kunde) {
								echo '<pre>';
								print_r($kunde);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>
					<?php
				}

				/**
				 * Partner
				 */
				if (!$t::isEmpty($kunde->partner[0]->id)) {
					($t::_g('p')) ? $idPartner = $t::_g('p') : $idPartner = $kunde->partner[0]->id;
					$partner = $api->getPartner($idPartner);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								$partnerFirma1 = $partner->firma1;
								if ($partner instanceof \loci\api\lib\Partner && !empty($partnerFirma1))
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#partner')">Partner</h2>
						</div>
						<div class="col-md-12">
							<?php
							$action = "partner";
							$form   = '<form action="'.$actual_link.'" method="get">';
							$form   .= '	<label for="switch_'.$action.'">Ändern</label>';
							$form   .= '	<select id="switch_'.$action.'" name="p">';
							if (is_array($kunde->partner)) {
								foreach ($kunde->partner as $xpart) {
									($xpart->id == $idPartner) ? $extra = 'selected' : $extra = '';
									$form .= '<option value="'.$xpart->id.'" '.$extra.'>'.$xpart->firma1.' (#'.$xpart->id.')</option>';
								}
							}
							$form .= '	</select>';
							if ($t::_g('k'))
								$form .= '<input type="hidden" name="k" value="'.$t::_g('k').'">';
							$form .= '<input type="hidden" name="t" value="'.$t::_g('t').'">';
							$form .= '</form>';
							echo $form;
							?>
						</div>
						<div class="col-md-12" id="partner" style="display: none;">
							<?php
							if ($partner) {
								echo '<pre>';
								print_r($partner);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>
					<?php
				}

				/**
				 * Kampagnen
				 */
				if (!$t::isEmpty($partner->kampagnen[0]->id)) {
					($t::_g('c')) ? $idKampagne = $t::_g('c') : $idKampagne = $partner->kampagnen[0]->id;
					$kampagne = $api->getKampagne($idKampagne);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								$kampagneFirma1 = $kampagne->firma1;
								if ($kampagne instanceof \loci\api\lib\Kampagne && !empty($kampagneFirma1))
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#kampagne')">Kampagne</h2>
						</div>
						<div class="col-md-12">
							<?php
							$action = "kampagne";

							$form = '<form action="'.$actual_link.'" method="get">';
							$form .= '	<label for="switch_'.$action.'">Ändern</label>';
							$form .= '	<select id="switch_'.$action.'" name="c">';
							if (is_array($partner->kampagnen)) {
								foreach ($partner->kampagnen as $xkampagne) {
									($xkampagne->id == $idKampagne) ? $extra = 'selected' : $extra = '';
									$form .= '<option value="'.$xkampagne->id.'" '.$extra.'>'.$xkampagne->firma1.' (#'.$xkampagne->id.')</option>';
								}
							}
							$form .= '	</select>';
							if ($t::_g('k'))
								$form .= '<input type="hidden" name="k" value="'.$t::_g('k').'">';
							if ($t::_g('p'))
								$form .= '<input type="hidden" name="p" value="'.$t::_g('p').'">';
							$form .= '<input type="hidden" name="t" value="'.$t::_g('t').'">';
							$form .= '</form>';
							echo $form;
							?>
						</div>
						<div class="col-md-12" id="kampagne" style="display: none;">
							<?php
							if ($kampagne) {
								echo '<pre>';
								print_r($kampagne);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>

					<?php
				}

				/**
				 * Aktion
				 */
				if (!$t::isEmpty($kampagne->aktionen[0]->id)) {
					($t::_g('a')) ? $idAktion = $t::_g('a') : $idAktion = $kampagne->aktionen[0]->id;
					$aktion = $api->getAktion($idAktion);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								$aktionFirma1 = $aktion->firma1;
								if ($aktion instanceof \loci\api\lib\Aktion && !empty($aktionFirma1))
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#aktion')">Aktion</h2>
						</div>
						<div class="col-md-12">
							<?php
							$action = "aktion";

							$form = '<form action="'.$actual_link.'" method="get">';
							$form .= '	<label for="switch_'.$action.'">Ändern</label>';
							$form .= '	<select id="switch_'.$action.'" name="a">';
							if (is_array($kampagne->aktionen)) {
								foreach ($kampagne->aktionen as $xaktion) {
									($xaktion->id == $idAktion) ? $extra = 'selected' : $extra = '';
									$form .= '<option value="'.$xaktion->id.'" '.$extra.'>'.$xaktion->titel.' (#'.$xaktion->id.')</option>';
								}
							}
							$form .= '	</select>';
							if ($t::_g('c'))
								$form .= '<input type="hidden" name="c" value="'.$t::_g('c').'">';
							if ($t::_g('k'))
								$form .= '<input type="hidden" name="k" value="'.$t::_g('k').'">';
							if ($t::_g('p'))
								$form .= '<input type="hidden" name="p" value="'.$t::_g('p').'">';
							$form .= '<input type="hidden" name="t" value="'.$t::_g('t').'">';
							$form .= '</form>';
							echo $form;
							?>
						</div>
						<div class="col-md-12" id="aktion" style="display: none;">
							<?php
							if ($aktion) {
								echo '<pre>';
								print_r($aktion);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>

					<?php
				}

				$fMail = 'api.test@bot.app-sharing.com';

				/**
				 * Teilnehmer löschen
				 */
				if (!$t::isEmpty($idAktion)) {
					$teilnehmerExists = $api->getTeilnehmer(['idAktion' => $idAktion, 'email' => $fMail, 'deleted'=>1]);
					$zid = $teilnehmerExists->_id;
					if (!empty($zid)) {
						$teilnehmerDeleteExisting = $api->deleteTeilnehmer(['idAktion' => $idAktion, '_id' => $zid, 'safe' => 1]);
						?>
						<div class="row">
							<div class="col-md-1">
								<h2>
									<?php
									if (!$teilnehmerDeleteExisting || $teilnehmerDeleteExisting = NULL || $teilnehmerDeleteExisting =="0"|| $teilnehmerDeleteExisting ==0)
										echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
									else
										echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
									?>
								</h2>
							</div>
							<div class="col-md-11">
								<h2 class="pointer" onclick="toggleApi('#tlnDeletePre')">Teilnehmer vorher löschen</h2>
							</div>
							<div class="col-md-12" id="tlnDeletePre" style="display: none;">
								<?php
								if ($teilnehmerDeleteExisting) {
									echo '<pre>';
									print_r($teilnehmerDeleteExisting);
									echo '</pre>';
								} else {
									echo '<h4>Errors:</h4>'."\r\n";
									echo '<pre>';
									print_r($api->getError());
									echo '</pre>';
								}
								?>
							</div>
						</div>
						<?php
					}
				}

				/**
				 * Teilnehmer erstellen
				 */

				if (!$t::isEmpty($idAktion)) {
					$teilnehmerCr           = new \loci\api\lib\Teilnehmer();
					$teilnehmerCr->vorname  = 'CMSAPI';
					$teilnehmerCr->nachname = 'Systemtest';
					$teilnehmerCr->email    = $fMail;
					$teilnehmerCreate       = $api->createTeilnehmer($idAktion, $teilnehmerCr);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								if ($teilnehmerCreate instanceof \loci\api\lib\Teilnehmer && $teilnehmerCr->vorname == 'CMSAPI')
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#tlnCreate')">Teilnehmer erstellen</h2>
						</div>
						<div class="col-md-12" id="tlnCreate" style="display: none;">
							<?php
							if ($teilnehmerCreate) {
								echo '<pre>';
								print_r($teilnehmerCreate);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>
					<?php
					$teilnehmerRead = $api->getTeilnehmer(['idAktion' => $idAktion, 'email' => $fMail]);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								if ($teilnehmerRead instanceof \loci\api\lib\Teilnehmer && $teilnehmerCr->vorname == 'CMSAPI')
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#tlnRead')">Teilnehmer auslesen</h2>
						</div>
						<div class="col-md-12" id="tlnRead" style="display: none;">
							<?php
							if ($teilnehmerRead) {
								echo '<pre>';
								print_r($teilnehmerRead);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>
					<?php
					$aktionsDaten = [
						'id' => $idAktion,
						'optin' => 1,
						'date' => date('d.m.Y'),
						'ip' => $_SERVER["REMOTE_ADDR"]
					];
					$aktionSet = $api->setAktionsDaten($teilnehmerRead, $idAktion, $aktionsDaten);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								if ($aktionSet)
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								else
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#tlnAktionData')">Aktionsdaten setzen</h2>
						</div>
						<div class="col-md-12" id="tlnAktionData" style="display: none;">
							<?php
							if ($aktionSet) {
								echo '<pre>';
								print_r($aktionSet);
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>
					<?php
					$teilnehmerDelete = $api->deleteTeilnehmer(['idAktion' => $idAktion, '_id' => $teilnehmerRead->id]);
					$errorsDelete     = $api->getError();
					$teilnehmerCheck  = $api->getTeilnehmer(['idAktion' => $idAktion, 'email' => $fMail]);
					?>
					<div class="row">
						<div class="col-md-1">
							<h2>
								<?php
								if ($teilnehmerCheck instanceof \loci\api\lib\Teilnehmer && $teilnehmerCr->email == $fMail)
									echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
								else
									echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
								?>
							</h2>
						</div>
						<div class="col-md-11">
							<h2 class="pointer" onclick="toggleApi('#tlnDelete')">Teilnehmer löschen</h2>
						</div>
						<div class="col-md-12" id="tlnDelete" style="display: none;">
							<?php
							if ($teilnehmerDelete && !$teilnehmerCheck) {
								echo '<pre>';
								print_r("Deleted");
								echo '</pre>';
							} else {
								echo '<h4>Errors:</h4>'."\r\n";
								echo '<pre>';
								print_r($errorsDelete);
								echo '</pre>';

								echo '<pre>';
								print_r($api->getError());
								echo '</pre>';
							}
							?>
						</div>
					</div>

					<?php
				}

			endif;
			?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		$("select").on('change', function () {
			$(this).parent().submit();
		});
	});
</script>
</body>
</html>

