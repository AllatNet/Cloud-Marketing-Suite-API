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
 * @property string    $vorname
 * @property string    $nachname
 * @property string    $email
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