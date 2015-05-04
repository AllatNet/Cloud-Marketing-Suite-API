<?php
/**
 * Created by PhpStorm.
 * User: Christian Höfer
 * Date: 20.04.2015
 * Time: 13:09
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
 * @property array   $kunden
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
		if ($name == 'attributes' && is_array($value)) {
			$this->_attributes = $value;
		} else {
			$this->_attributes[$name] = $value;
		}

		return true;
	}
}