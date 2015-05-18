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
	 * Token der für die Schnittstellenbefragung notwendig ist
	 * @see __construct()
	 * @var null|string
	 */
	private $token = null;

	/**
	 * Url zur API des Entwicklungssystem
	 * @var string
	 */
	private $host_dev = 'https://www.fb-sites.com/development/backend/web/api';

	/**
	 * Url zur API des Live-Systems
	 * @var string
	 */
	private $host_prod = 'https://www.fb-sites.com/suite/backend/web/api';

	/**
	 * Initialisiert die Schnittstelle. Jede Schnittstellenabfrage läuft über diese Instanz.
	 *
	 * @param string $token
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
	 * Wenn True gesetzt wird, wird die Entwicklungsumgebung abgefragt
	 *
	 * @param bool $dev
	 */
	public function isDev($dev = false) {
		$this->dev = (boolean)$dev;
	}

	/**
	 * Holt die Aktionsdaten zu einem Teilnehmer.
	 *
	 * @param \loci\api\lib\Teilnehmer $teilnehmer
	 * @param integer                  $idAktion
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getAktionsDaten($teilnehmer, $idAktion) {
		if (!$teilnehmer instanceof Teilnehmer) {
			throw new \Exception('Attribut 1 muss ein Objekt vom Typ "Teilnehmer" sein.');
		}
		$data = $this->request(['idAktion' => $idAktion, 'tln' => $teilnehmer->attributes['_id']]);

		return (array)json_decode($data);
	}

	/**
	 * @param \loci\api\lib\Teilnehmer $teilnehmer
	 * @param integer                  $idAktion
	 * @param array                    $aktionsDaten
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function setAktionsDaten($teilnehmer, $idAktion, $aktionsDaten) {
		if (!$teilnehmer instanceof Teilnehmer) {
			throw new \Exception('Attribut 1 muss ein Objekt vom Typ "Teilnehmer" sein.');
		}
		$data = $this->request(['idAktion' => $idAktion, 'aktionsDaten' => $aktionsDaten, 'tln' => $teilnehmer->attributes['_id']]);

		return $this->getAktionsDaten($teilnehmer, $idAktion);
	}

	/**
	 * @param array $data
	 *
	 * @return  \loci\api\lib\Teilnehmer
	 * @throws \Exception
	 */
	public function updateTeilnehmer($data) {
		if (!$data instanceof Teilnehmer) {
			throw new \Exception('Attribut 1 muss ein Objekt vom Typ "Teilnehmer" sein.');
		}
		$data            = $this->request($data->attributes);
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = (array)json_decode($data);

		return $tln;
	}

	/**
	 * @param array $data
	 *
	 * @return \loci\api\lib\Teilnehmer
	 */
	public function getTeilnehmer($data) {
		$data            = $this->request($data);
		$tln             = new Teilnehmer();
		$tln->token      = $this->token;
		$tln->attributes = json_decode($data);

		return $tln;
	}

	/**
	 * @param integer $idAktion
	 *
	 * @return Aktion
	 */
	public function getAktion($idAktion) {
		$data               = $this->request(['idAktion' => $idAktion]);
		$aktion             = new Aktion();
		$aktion->attributes = json_decode($data);

		return $aktion;
	}

	/**
	 * @param integer $idKampagne
	 *
	 * @return Kampagne
	 */
	public function getKampagne($idKampagne) {
		$data                 = $this->request(['idKampagne' => $idKampagne]);
		$kampagne             = new Kampagne();
		$kampagne->attributes = json_decode($data);

		return $kampagne;
	}

	/**
	 * @param integer $idPartner
	 *
	 * @return Partner
	 */
	public function getPartner($idPartner) {
		$data                = $this->request(['idPartner' => $idPartner]);
		$partner             = new Partner();
		$partner->attributes = json_decode($data);

		return $partner;
	}

	/**
	 * @param integer $idKunde
	 *
	 * @return Kunde
	 */
	public function getKunde($idKunde) {
		$data              = $this->request(['idKunde' => $idKunde]);
		$kunde             = new Kunde();
		$kunde->attributes = json_decode($data);

		return $kunde;
	}

	/**
	 * @return Mandant
	 */
	public function getMandant() {
		$data                = $this->request();
		$mandant             = new Mandant();
		$mandant->attributes = json_decode($data);

		return $mandant;
	}

	private function request($data = []) {
		if ($this->dev) {
			$host = $this->host_dev;
		} else {
			$host = $this->host_prod;
		}
		$ch      = curl_init();
		$callers = debug_backtrace();
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
			case 'getTeilnehmer':
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
			default:
				echo $callers[1]['function'];
		}

		session_write_close();
		$return = curl_exec($ch);
		if (!curl_errno($ch)) {
			$responseHeader = curl_getinfo($ch);
			if($responseHeader['http_code'] != 200){
				$data = (array)json_decode($return);
				$this->sendException($responseHeader['http_code'], $data['message']);
			}
		}
		session_start();

		curl_close($ch);

		return $return;
	}

	private function sendException($code, $message) {
		throw new ApiException($code, $message);
	}

}