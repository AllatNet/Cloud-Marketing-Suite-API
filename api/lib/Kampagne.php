<?php
/**
 * Created by PhpStorm.
 * User: Christian Höfer
 * Date: 20.04.2015
 * Time: 13:15
 */

namespace loci\api\lib;

/**
 * Class Kampagne
 * @package loci\api\lib
 *
 * @property string    $title
 * @property integer   $idPartner
 * @property string    $firma1
 * @property string    $firma2
 * @property string    $firma3
 * @property string    $logo
 * @property string    $banner
 * @property string    $analytics
 * @property string    $cleverreachWsdl
 * @property string    $cleverreachListId
 * @property string    $cleverreachFormId
 * @property string    $cleverreachApiKey
 * @property string    $agbLink
 * @property string    $agbText
 * @property string    $impressumLink
 * @property string    $impressumText
 * @property string    $datenschutzLink
 * @property string    $datenschutzText
 * @property string    $template
 * @property string    $strasse
 * @property string    $plz
 * @property string    $ort
 * @property string    $land
 * @property string    $email
 * @property string    $telefon
 * @property string    $fax
 * @property string    $website
 * @property string    $facebookSeite
 * @property string    $youtubeChannel
 * @property string    $googlePlusSeite
 * @property string    $twitterSeite
 * @property string    $xingSeite
 * @property string    $idCrm
 * @property array     $aktionen
 */

class Kampagne {


	/**
	 * Attribute der Kampagne
	 * @var array
	 */
	private $_attributes = [];

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function __get($name) {
		if (isset($this->_attributes[$name])) {
			return $this->_attributes[$name];
		} else {
			return '';
		}
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public function __set($name, $value) {
		if ($name == 'attributes') {
			if (!is_array($value))
				$value = (array)$value;
			$this->_attributes = $value;
			if (isset($this->_attributes['ebenenFelder']) && is_object($this->_attributes['ebenenFelder'])) {
				foreach ($this->_attributes['ebenenFelder'] as $feld => $value) {
					$this->_attributes[$feld] = $value;
				}
				unset($this->_attributes['ebenenFelder']);
			}
		} else {
			$this->_attributes[$name] = $value;
		}

		return true;
	}
}