<?php
/**
 * Created by PhpStorm.
 * User: Christian Höfer
 * Date: 20.04.2015
 * Time: 13:15
 */

namespace loci\api\lib;

/**
 * Class Teilnehmer
 * @package loci\api\lib
 *
 *
 * @property string    $id
 * @property string    $anrede
 * @property string    $titel
 * @property string    $vorname
 * @property string    $nachname
 * @property string    $strasse
 * @property string    $plz
 * @property string    $ort
 * @property string    $land
 * @property string    $geburtstag
 * @property string    $email
 * @property string    $optin
 */
class Teilnehmer {


	/**
	 * Attribute des Partners
	 * @var array
	 */
	private $_attributes = [];
	private $_token = null;

	/**
	 * @param $name
	 *
	 * @return string
	 */
	public function __get($name) {
		switch($name){
			case 'attributes':
				return $this->_attributes;
				break;
			case 'id':
				return $this->_attributes['_id'];
				break;
			default:
				if (isset($this->_attributes[$name])) {
					return $this->_attributes[$name];
				} else {
					return '';
				}
		}
	}

	/**
	 * @param $name
	 * @param $value
	 *
	 * @return bool
	 */
	public function __set($name, $value) {
		switch($name){
			case 'attributes':
				if (!is_array($value))
					$value = (array)$value;
				$this->_attributes = $value;
			break;
			case 'token':
				$this->_token = $value;
				break;
			default:
				$this->_attributes[$name] = $value;
				return true;
		}
	}
}