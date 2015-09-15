<?php
/**
 * Created by PhpStorm.
 * User: Christian Höfer
 * Date: 20.04.2015
 * Time: 13:03
 *
 * @version 1.0.0
 */


namespace loci\api;


use loci\api\lib\Aktion;
use loci\api\lib\ApiException;
use loci\api\lib\Kampagne;
use loci\api\lib\Kunde;
use loci\api\lib\Mandant;
use loci\api\lib\Partner;
use loci\api\lib\Teilnehmer;

class API
{

	/**
	 * Entscheidet ob die Live oder die Entwicklungsumgebung abgefragt werden soll. Wird über isDev() bedient.
	 * @see isDev()
	 * @var bool
	 */
	private $dev = false;

	/**
	 * Fehlermeldung bei der Abfrage der Schnittstelle
	 * @var string
	 */
	private $errorMessage = '';

	/**
	 * Fehlernummer bei der Abfrage der Schnittstelle
	 * @var int
	 */
	private $errorNo = 0;

	/**
	 * Url zur API des Entwicklungssystem
	 * @var string
	 */
	private $host_dev = 'https://www.app-sharing.com/development/backend/web/api';

	/**
	 * Url zur API des Live-Systems
	 * @var string
	 */
	private $host_prod = 'https://www.app-sharing.com/suite/backend/web/api';

	/**
	 * Token der für die Schnittstellenbefragung notwendig ist
	 * @see __construct()
	 * @var null|string
	 */
	private $token = null;

	/**
	 * Initialisiert die Schnittstelle. Jede Schnittstellenabfrage läuft über diese Instanz.
	 *
	 * @param string $token - Token ist von Ihrem Cloud-Marketing-Suite Mandanten zu erhalten
	 */
	public function __construct($token) {
		require_once(__DIR__.'/lib/Aktion.php');
		require_once(__DIR__.'/lib/Kampagne.php');
		require_once(__DIR__.'/lib/Kunde.php');
		require_once(__DIR__.'/lib/Mandant.php');
		require_once(__DIR__.'/lib/Partner.php');
		require_once(__DIR__.'/lib/Teilnehmer.php');
		require_once(__DIR__.'/lib/ApiException.php');
		$this->token = $token;
	}

	/**
	 * Erstellt einen Teilnehmer in der Datenbank
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $teilnehmer = new Teilnehmer()
	 * $teilnehmer->vorname = 'Max';
	 * $api->createTeilnehmer($idAktion, $teilnehmer);
	 * ```
	 *
	 * @param            $idAktion   - ID der Aktion
	 * @param Teilnehmer $teilnehmer - Teilnehmer Objekt
	 *
	 * @return Teilnehmer|null
	 * * Erfolgreich: Aktuelles Teilnehmer Objekt
	 * * Fehler: NULL
	 */
	public function createTeilnehmer($idAktion, Teilnehmer $teilnehmer) {
		$data             = $teilnehmer->attributes;
		$data['idAktion'] = $idAktion;
		$return           = $this->request($data);
		if (!empty($this->errorNo))
			return null;
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = (array)json_decode($return);

		return $tln;
	}

	/**
	 * Holt die Aktionsdaten zu der via ID angegebenen Aktion
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getAktion($idAktion);
	 * ```
	 *
	 * @param integer $idAktion - ID der Aktion
	 *
	 * @return Aktion
	 * * Erfolgreich: Objekt vom Typ Aktion
	 * * Fehler: NULL
	 */
	public function getAktion($idAktion) {
		$data = $this->request(['idAktion' => $idAktion]);
		if (!empty($this->errorNo))
			return null;
		$aktion             = new Aktion();
		$aktion->attributes = json_decode($data);

		return $aktion;
	}

	/**
	 * Holt entsprechende Aktionsdaten zu einem Teilnehmer
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * $api->getAktionsDaten($teilnehmer, $idAktion)]
	 * ```
	 *
	 * @param \loci\api\lib\Teilnehmer $teilnehmer - Teilnehmer Objekt
	 * @param integer                  $idAktion   - ID der Aktion
	 *
	 * @return array|null
	 * * Erfolgreich: Array mit den Aktionsdaten
	 * * Fehler: NULL
	 */
	public function getAktionsDaten($teilnehmer, $idAktion) {
		if (!$teilnehmer instanceof Teilnehmer) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Der Parameter $teilnehmer muss ein Objekt vom Typ \loci\api\lib\Teilnehmer sein';

			return null;
		}
		$data = $this->request(['idAktion' => $idAktion, 'tln' => $teilnehmer->attributes['_id']]);
		if (!empty($this->errorNo))
			return null;

		return (array)json_decode($data);
	}

	/**
	 *
	 * Gibt aufgetretene Fehlermeldungen zurück
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getKunde($idKunde);
	 * $errors = $api->getError();
	 * ```
	 * Die Variable $error enthält jetzt folgendes Array:
	 * <pre>
	 * Array
	 * (
	 *    [code] => "Fehlercode"
	 *    [message] => "Fehlermeldung"
	 * )
	 * </pre>
	 *
	 * @return array
	 */
	public function getError() {
		return [
			'code'    => $this->errorNo,
			'message' => $this->errorMessage,
		];
	}

	/**
	 * Holt die Kampagnendaten zu der via ID angegebenen Kampagne
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getKampagne($idKampagne);
	 * ```
	 *
	 * @param integer $idKampagne - ID der Kampagne
	 *
	 * @return Kampagne
	 * * Erfolgreich: Objekt vom Typ Kampagne
	 * * Fehler: NULL
	 */
	public function getKampagne($idKampagne) {
		$data = $this->request(['idKampagne' => $idKampagne]);
		if (!empty($this->errorNo))
			return null;
		$kampagne             = new Kampagne();
		$kampagne->attributes = json_decode($data);

		return $kampagne;
	}

	/**
	 * Holt die Kundendaten zu dem via ID angegebenen Kunden
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getKunde($idKunde);
	 * ```
	 *
	 * @param integer $idKunde - ID des Kunden
	 *
	 * @return Kunde
	 * * Erfolgreich: Objekt vom Typ Kunde
	 * * Fehler: NULL
	 */
	public function getKunde($idKunde) {
		$data = $this->request(['idKunde' => $idKunde]);
		if (!empty($this->errorNo))
			return null;
		$kunde             = new Kunde();
		$kunde->attributes = json_decode($data);

		return $kunde;
	}

	/**
	 * Holt die Mandantendaten zu dem via ID angegebenen Mandanten
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getMandant();
	 * ```
	 * @return Mandant
	 * * Erfolgreich: Objekt vom Typ Mandant
	 * * Fehler: NULL
	 */
	public function getMandant() {
		$data = $this->request();

		if (!empty($this->errorNo))
			return null;
		$mandant             = new Mandant();
		$mandant->attributes = json_decode($data);

		return $mandant;
	}

	/**
	 * Holt die Partnerdaten zu dem via ID angegebenen Partner
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $api->getPartner($idPartner);
	 * ```
	 *
	 * @param integer $idPartner - ID des Partners
	 *
	 * @return Partner
	 * * Erfolgreich: Objekt vom Typ Partner
	 * * Fehler: NULL
	 */
	public function getPartner($idPartner) {
		$data = $this->request(['idPartner' => $idPartner]);
		if (!empty($this->errorNo))
			return null;
		$partner             = new Partner();
		$partner->attributes = json_decode($data);

		return $partner;
	}

	/**
	 * Holt die Teilnehmerdaten eines angegebenen Teilnehmers
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * // Selektion anhand E-Mail Adresse
	 * $config = ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 * // Selektion anhand Hash
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * ```
	 *
	 * @param array $data - Config Array des Teilnehmers, mindestens jedoch zwei Parameter<br />
	 *                    Dabei ist idAktion Pflicht!<br />
	 *                    ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 *
	 * @return \loci\api\lib\Teilnehmer
	 * * Erfolgreich: Aktuelles Teilnehmer Objekt
	 * * Fehler: NULL
	 */
	public function getTeilnehmer($data) {
		if (empty($data['idAktion'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit idAktion gefunden werden';

			return null;
		}
		if (count($data) < 2) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit mindestens zwei Attributen gesucht werden';

			return null;
		}

		$data = $this->request($data);

		if (!empty($this->errorNo))
			return null;
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = json_decode($data);

		return $tln;
	}

	/**
	 * Holt die Teilnehmerdaten eines angegebenen Teilnehmers unter Berücksichtigung dass der Teilnehmer unter dem Partner zu finden ist.
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * // Selektion anhand E-Mail Adresse
	 * $config = ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 * // Selektion anhand Hash
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * ```
	 *
	 * @param array $data - Config Array des Teilnehmers, mindestens jedoch zwei Parameter<br />
	 *                    Dabei ist idAktion Pflicht!<br />
	 *                    ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 *
	 * @return \loci\api\lib\Teilnehmer
	 * * Erfolgreich: Aktuelles Teilnehmer Objekt
	 * * Fehler: NULL
	 */
	public function getTeilnehmerStammdaten($data) {
		if (empty($data['idAktion'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit idAktion gefunden werden';

			return null;
		}
		if (count($data) < 2) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit mindestens zwei Attributen gesucht werden';

			return null;
		}

		$data = $this->request($data);

		if (!empty($this->errorNo))
			return null;
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = json_decode($data);

		return $tln;
	}

	/**
	 * Holt alle Teilnehmer-Hash'es die im gewählten Zeitraum geändert wurden
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * // Selektion aller Teilnehmer seit 01.01.2015 -> 1420070400
	 * $config = ['idAktion'=>$idAktion, 'from'=>'1420070400']
	 * // Selektion aller geänderten Teilnehmer zwischem dem 01.01.2015 und dem 31.01.2015
	 * $config = ['idAktion'=>$idAktion, 'from'=>'1420070400', 'to'=>'1422748799']
	 * $teilnehmer = $api->getTeilnehmerChanged($config);
	 * ```
	 *
	 * @param array $data - Config Array des Teilnehmers, mindestens jedoch zwei Parameter<br />
	 *                    Dabei ist idAktion Pflicht!<br />
	 *                    ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 *
	 * @return array
	 * * Array mit Hashes
	 * * Fehler: NULL
	 */
	public function getTeilnehmerChanged($data) {
		if (empty($data['idAktion'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit idAktion gefunden werden';

			return null;
		}
		if (empty($data['from'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Datum von muss gesetzt und eine Zahl sein';

			return null;
		}

		$data = $this->request($data);

		if (!empty($this->errorNo))
			return null;

        return (array)json_decode($data);
	}

	/**
	 * Löscht einen Teilnehmer in der Cloud-Marketing-Suite
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * // Löschung anhand Hash
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $return = $api->deleteTeilnehmer($config);
	 * ```
	 *
	 * @param array $data - Konfigurations Array für die Löschung des Teilnehmers, darf nur idAktion und _id enthalten.
	 *
	 * @return bool|null
	 */
	public function deleteTeilnehmer($data) {
		if (empty($data['idAktion'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur mit idAktion gefunden werden';

			return null;
		}
		if (empty($data['_id'])) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Ein Teilnehmer kann nur über seinen Hash gelöscht werden';

			return null;
		}

		$data = $this->request($data);

		if (!empty($this->errorNo))
			return null;
		return true;
	}

	/**
	 * Wenn True gesetzt wird, wird die Entwicklungsumgebung abgefragt
	 *
	 * @param bool $dev
	 */
	public function isDev($dev = false) {
		$this->dev = (boolean)$dev;
	}

	private function request($data = []) {
		$this->errorNo      = 0;
		$this->errorMessage = '';
		if ($this->dev) {
			$host = $this->host_dev;
		} else {
			$host = $this->host_prod;
		}
		$ch      = curl_init();
		$callers = debug_backtrace();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		switch ($callers[1]['function']) {
			case 'getAktionsDaten':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&i='.$data['tln'].'&ai='.$data['idAktion']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'setAktionsDaten':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&i='.$data['tln'].'&ai='.$data['idAktion'].'&d='.urlencode(json_encode($data['aktionsDaten'])));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'updateTeilnehmer':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&i='.$data['_id'].'&d='.urlencode(json_encode($data)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'createTeilnehmer':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.urlencode(json_encode($data)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getTeilnehmer':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'deleteTeilnehmer':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getTeilnehmerStammdaten':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getTeilnehmerChanged':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getAktion':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&a='.$data['idAktion']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getKampagne':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&k='.$data['idKampagne']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getPartner':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&p='.$data['idPartner']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getMandant':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'getKunde':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&k='.$data['idKunde']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			case 'sendMail':
				curl_setopt($ch, CURLOPT_URL, $host.'/'.strtolower($callers[1]['function']).'?t='.$this->token.'&d='.urlencode(json_encode($data)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			default:
				echo $callers[1]['function'];
		}

		$return = curl_exec($ch);


		if (!curl_errno($ch)) {
			$responseHeader = curl_getinfo($ch);
			if ($responseHeader['http_code'] != 200) {
				$data               = (array)json_decode($return);
				$this->errorNo      = $responseHeader['http_code'];
				$this->errorMessage = $data['message'];
			}
		}else{
			$this->errorNo = curl_errno($ch);
			$this->errorMessage = curl_error($ch);
		}

		curl_close($ch);

		return $return;
	}

	/**
	 * Sendet einem Teilnehmer eine E-mail
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * $mailConfig = [
	 *
	 *    'from'=>'Name <from@example.com>',
	 *
	 *    'to'=>'Name <to@example.com>',
	 *
	 *    'subject'=>'Deine Registrierung',
	 *
	 *    'text'=>'Hallo, Deine Registrierung war erfolgreich......',
     *
 * 	      'html'=>false,
	 * ]
	 * $api->sendMail($teilnehmer, $idAktion, $mailConfig);
	 * ```
	 *
	 * @param Teilnehmer $teilnehmer
	 * @param integer $idAktion
	 * @param array    $conf
	 *
	 * @return true|false
	 * * Erfolgreich: TRUE
	 * * Fehler: FALSE
	 */
	public function sendMail(Teilnehmer $teilnehmer, $idAktion, $conf) {
		$this->request(['mail' => $conf, 'aktion'=>$idAktion, 'user' => $teilnehmer->id]);
		if (!empty($this->errorNo))
			return false;

		return true;
	}

	/**
	 * Setzt entsprechende Aktionsdaten zu einem Teilnehmer
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * $api->setAktionsDaten($teilnehmer, $idAktion, ['teilgenommen'=>date('d.m.Y H:i:s'])]
	 * ```
	 *
	 * @param \loci\api\lib\Teilnehmer $teilnehmer   - Teilnehmer Objekt
	 * @param integer                  $idAktion     - ID der Aktion
	 * @param array                    $aktionsDaten - Array mit Daten
	 *
	 * @return array
	 * * Erfolgreich: Array mit den Aktionsdaten
	 * * Fehler: NULL
	 */
	public function setAktionsDaten($teilnehmer, $idAktion, $aktionsDaten) {
		if (!$teilnehmer instanceof Teilnehmer) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Der Parameter $teilnehmer muss ein Objekt vom Typ \loci\api\lib\Teilnehmer sein';

			return null;
		}
		$data = $this->request(['idAktion' => $idAktion, 'aktionsDaten' => $aktionsDaten, 'tln' => $teilnehmer->attributes['_id']]);
		if (!empty($this->errorNo))
			return null;

		return $this->getAktionsDaten($teilnehmer, $idAktion);
	}

	/**
	 * Ändert die Teilnehmerdaten eines Teilnehmers
	 *
	 * Beispiel:
	 * ```php
	 * $api = new API("my-secret-token");
	 * // Selektion anhand E-Mail Adresse
	 * $config = ['idAktion'=>$idAktion, 'email'=>'example@example.com']
	 * // Selektion anhand Hash
	 * $config = ['idAktion'=>$idAktion, '_id'=>'user-hash']
	 * $teilnehmer = $api->getTeilnehmer($config);
	 * $teilnehmer->vorname = 'Max';
	 * $api->updateTeilnehmer($teilnehmer);
	 * ```
	 *
	 * @param \loci\api\lib\Teilnehmer $teilnehmer - Teilnehmer Objekt
	 *
	 * @return  \loci\api\lib\Teilnehmer
	 * * Erfolgreich: Aktuelles Teilnehmer Objekt
	 * * Fehler: NULL
	 */
	public function updateTeilnehmer(Teilnehmer $teilnehmer) {
		if (!$teilnehmer instanceof Teilnehmer) {
			$this->errorNo      = 400;
			$this->errorMessage = 'Der Parameter $teilnehmer muss ein Objekt vom Typ \loci\api\lib\Teilnehmer sein';

			return null;
		}
		$data = $this->request($teilnehmer->attributes);
		if (!empty($this->errorNo))
			return null;
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = (array)json_decode($data);

		return $tln;
	}
}