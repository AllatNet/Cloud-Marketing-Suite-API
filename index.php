<html>
<head>
    <title>API-Test</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script type="text/javascript">
        function toggleApi(id){
            var element = $(id);
            console.log(element.css('display'));
            if(element.css('display') == 'none'){
                element.show(250);
            }else{
                element.hide(250);
            }

        }
    </script>
</head>
<body>
<div class="container" style="width: 1000px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">

            <?php

            ini_set('display_errors', 1);
            ini_set('error_reporting', E_ALL);

            require(__DIR__.'/api/API.php');
            // DEV Token
            $api = new \loci\api\API('9097b7c946794856508f05e3506d9bfb');

            // LIVE Token
//            $api = new \loci\api\API('6b62418e656f425229aa518012c349d5');

            $api->isDev(true);

            ?>
            <div class="row">
                <div class="col-md-12">
                    <h1>API-Test</h1>
                    <table class="table table-bordered">
                        <tr>
                            <td>API-Token</td>
                            <td>9405205982830810d21939b2a3f0ec3e</td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php
            $mandant = $api->getMandant();
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        $mandantenFirma = $mandant->firma;
                        if($mandant instanceof \loci\api\lib\Mandant && !empty($mandantenFirma))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#mandant')">Mandant</h2>
                </div>
                <div class="col-md-12" id="mandant" style="display: none;">
                    <?php
                    if ($mandant) {
                        echo '<pre>';
                        print_r($mandant);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $kunde = $api->getKunde($mandant->kunden[0]->id);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        $kundenFirma1 = $kunde->firma1;
                        if($kunde instanceof \loci\api\lib\Kunde && !empty($kundenFirma1))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#kunde')">Kunde</h2>
                </div>
                <div class="col-md-12" id="kunde" style="display: none;">
                    <?php
                    if ($kunde) {
                        echo '<pre>';
                        print_r($kunde);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $partner = $api->getPartner($kunde->partner[0]->id);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        $partnerFirma1 = $partner->firma1;
                        if($partner instanceof \loci\api\lib\Partner && !empty($partnerFirma1))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#partner')">Partner</h2>
                </div>
                <div class="col-md-12" id="partner" style="display: none;">
                    <?php
                    if ($partner) {
                        echo '<pre>';
                        print_r($partner);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $kampagne = $api->getKampagne(3);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        $kampagneFirma1 = $kampagne->firma1;
                        if($kampagne instanceof \loci\api\lib\Kampagne && !empty($kampagneFirma1))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#kampagne')">Kampagne</h2>
                </div>
                <div class="col-md-12" id="kampagne" style="display: none;">
                    <?php
                    if ($kampagne) {
                        echo '<pre>';
                        print_r($kampagne);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $aktion = $api->getAktion(117);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        $aktionFirma1 = $aktion->firma1;
                        if($aktion instanceof \loci\api\lib\Aktion && !empty($aktionFirma1))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#aktion')">Aktion</h2>
                </div>
                <div class="col-md-12" id="aktion" style="display: none;">
                    <?php
                    if ($aktion) {
                        echo '<pre>';
                        print_r($aktion);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerCr           = new \loci\api\lib\Teilnehmer();
            $teilnehmerCr->vorname  = 'CMSAPI';
            $teilnehmerCr->nachname = 'Systemtest';
            $teilnehmerCr->email    = 'team@allatnet.de';
            $teilnehmerCreate     = $api->createTeilnehmer(117, $teilnehmerCr);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if($teilnehmerCreate instanceof \loci\api\lib\Teilnehmer && $teilnehmerCr->vorname == 'CMSAPI')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnCreate')">Teilnehmer erstellen</h2>
                </div>
                <div class="col-md-12" id="tlnCreate" style="display: none;">
                    <?php
                    if ($teilnehmerCreate) {
                        echo '<pre>';
                        print_r($teilnehmerCreate);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerRead = $api->getTeilnehmer(['idAktion' => 117, 'email' => 'team@allatnet.de']);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if($teilnehmerRead instanceof \loci\api\lib\Teilnehmer && $teilnehmerCr->vorname == 'CMSAPI')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnRead')">Teilnehmer auslesen</h2>
                </div>
                <div class="col-md-12" id="tlnRead" style="display: none;">
                    <?php
                    if ($teilnehmerRead) {
                        echo '<pre>';
                        print_r($teilnehmerRead);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerUpdate = $api->getTeilnehmer(['idAktion' => 117, 'email' => 'team@allatnet.de']);
            $teilnehmerUpdate->vorname = 'CMSAPI2';
            $teilnehmerUpdated = $api->updateTeilnehmer($teilnehmerUpdate);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if($teilnehmerUpdated instanceof \loci\api\lib\Teilnehmer && $teilnehmerUpdated->vorname == 'CMSAPI2')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnUpdate')">Teilnehmer &auml;ndern</h2>
                </div>
                <div class="col-md-12" id="tlnUpdate" style="display: none;">
                    <?php
                    if ($teilnehmerUpdated) {
                        echo '<pre>';
                        print_r($teilnehmerUpdated);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerSetaktinosdaten = $api->getTeilnehmer(['idAktion' => 117, 'email' => 'team@allatnet.de']);
            $setAktionsdaten = $api->setAktionsDaten($teilnehmerSetaktinosdaten, 117, ['testfeld'=>'testwert']);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if(is_array($setAktionsdaten) && $setAktionsdaten['testfeld'] == 'testwert')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnSetaktionsdaten')">Teilnehmer aktionsdaten setzen</h2>
                </div>
                <div class="col-md-12" id="tlnSetaktionsdaten" style="display: none;">
                    <?php
                    if ($setAktionsdaten) {
                        echo '<pre>';
                        print_r($setAktionsdaten);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerGetaktionsdaten = $api->getTeilnehmer(['idAktion' => 117, 'email' => 'team@allatnet.de']);
            $getAktionsDaten = $api->getAktionsDaten($teilnehmerGetaktionsdaten, 117);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if(is_array($getAktionsDaten) && $getAktionsDaten['testfeld'] == 'testwert')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnGetaktionsdaten')">Teilnehmer aktionsdaten auslesen</h2>
                </div>
                <div class="col-md-12" id="tlnGetaktionsdaten" style="display: none;">
                    <?php
                    if ($getAktionsDaten) {
                        echo '<pre>';
                        print_r($getAktionsDaten);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerSendmail = $api->getTeilnehmer(['idAktion' => 117, 'email' => 'team@allatnet.de']);
            $teilnehmerSendmailSended = $api->sendMail($teilnehmerSendmail, 117, [
                'from'=>'Loci <kundenbetreuung@loci.biz>',
                'cc'=>[
                    'AllatNet Info <info@allatnet.de>',
                    'AllatNet Team <team@allatnet.de>',
                ],
                'bcc'=>[
                    'Christian Hoefer <ch@allatnet.de>',
                    'Christian Hoefer Team <team@allatnet.de>',
                ],
                'to'=>$teilnehmerSendmail->email,
                'subject'=>'API-Test E-Mail',
                'text'=>'Test E-Mail des aktuell laufenden Tests auf cmsapi.we-dev.de',
                'attachments'=>[
                    [
                        'url'=>'https://www.app-sharing.com/development/backend/web/images/logobanner.png',
                        'name'=>'logo.png',
                    ],
                ],
                'html'=>true,
            ]);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if($teilnehmerSendmailSended)
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnSendmail')">Teilnehmer E-Mail senden</h2>
                </div>
                <div class="col-md-12" id="tlnSendmail" style="display: none;">
                    <?php
                    if ($teilnehmerSendmailSended) {
                        echo '<pre>';
                        print_r($teilnehmerSendmailSended);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $config = [
                'idAktion' => 117,
                'from'     => (time()-20)*000,
            ];
            $hashes = $api->getTeilnehmerChanged($config);

            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if(is_array($hashes))
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnChanged')">Teilnehmer Ge&auml;nderte</h2>
                </div>
                <div class="col-md-12" id="tlnChanged" style="display: none;">
                    <?php
                    if ($hashes) {
                        echo '<pre>';
                        print_r($hashes);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerStammdaten = $api->getTeilnehmerStammdaten(['idAktion'=>117, 'email' => 'team@allatnet.de']);
            ?>
            <div class="row">
                <div class="col-md-1">
                    <h2>
                        <?php
                        if($teilnehmerStammdaten instanceof \loci\api\lib\Teilnehmer && $teilnehmerStammdaten->vorname == 'CMSAPI2')
                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
                        else
                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
                        ?>
                    </h2>
                </div>
                <div class="col-md-11">
                    <h2 onclick="toggleApi('#tlnStammdaten')">Teilnehmer Ge&auml;nderte</h2>
                </div>
                <div class="col-md-12" id="tlnStammdaten" style="display: none;">
                    <?php
                    if ($teilnehmerStammdaten) {
                        echo '<pre>';
                        print_r($teilnehmerStammdaten);
                        echo '</pre>';
                    } else {
                        echo '<pre>';
                        print_r($api->getError());
                        echo '</pre>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $teilnehmerDelete = $api->getTeilnehmer(['idAktion'=>117, 'email' => 'team@allatnet.de']);
//            $teilnehmerDeleted = $api->deleteTeilnehmer(['idAktion'=>117, '_id'=>$teilnehmerDelete->id]);
            $teilnehmerDeleted2 = $api->getTeilnehmer(['idAktion'=>117, 'email' => 'team@allatnet.de']);
//            echo '<pre>';
//            print_r($teilnehmerDeleted2);
//            echo '</pre>';
//            die();

            ?>
<!--            <div class="row">-->
<!--                <div class="col-md-1">-->
<!--                    <h2>-->
<!--                        --><?php
//                        if($teilnehmerDeleted)
//                            echo '<i class="glyphicon glyphicon-ok-circle text-success"></i>';
//                        else
//                            echo '<i class="glyphicon glyphicon-ban-circle text-danger"></i>';
//                        ?>
<!--                    </h2>-->
<!--                </div>-->
<!--                <div class="col-md-11">-->
<!--                    <h2 onclick="toggleApi('#tlnStammdaten')">Teilnehmer Ge&auml;nderte</h2>-->
<!--                </div>-->
<!--                <div class="col-md-12" id="tlnStammdaten" style="display: none;">-->
<!--                    --><?php
//                    if ($teilnehmerStammdaten) {
//                        echo '<pre>';
//                        print_r($teilnehmerStammdaten);
//                        echo '</pre>';
//                    } else {
//                        echo '<pre>';
//                        print_r($api->getError());
//                        echo '</pre>';
//                    }
//                    ?>
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
</body>
</html>
