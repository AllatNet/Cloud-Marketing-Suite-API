<?php
/**
 * Loci - Cloud Marketing Suite API
 * Lizenz: https://www.apache.org/licenses/LICENSE-2.0
 * Version: 1.3.0
 * Author: AllatNet Internetsysteme
 * Link: https://fb-sites.com/api/
 */
namespace loci\api\lib;

/**
 * Class Mandant
 * @package loci\api\lib
 *
 * @property integer $id
 * @property string  $firma
 * @property string  $logo
 * @property string  $strasse
 * @property integer $plz
 * @property string  $ort
 * @property string  $land
 * @property string  $telefon
 * @property string  $fax
 * @property string  $website
 * @property string  $email
 * @property array  $$kunden
 */
class Mandant
{

	/**
	 * Attribute der Mandanten
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
			$this->_attributes = (array)$value;
		} else {
			$this->_attributes[$name] = $value;
		}

		return true;
	}
}