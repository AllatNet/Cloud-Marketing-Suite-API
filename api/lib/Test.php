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
 * Class Test
 * @package loci\api\lib
 */
class Test {

    /** @var string "API-Schlüssel" */
	public $token;

	public function __construct() {
		ini_set('display_errors', 1);
		ini_set('error_reporting', E_ALL || ~E_NOTICE || ~E_WARNING);
	}

	/**
	 * Prüft ob eine Variable existiert
	 *
	 * @param mixed $var
	 *
	 * @return bool
	 */
	public static function isEmpty($var){
		if(isset($var) && !empty($var) && $var != NULL && $var != ' '){
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Holt GET variablen sicher ab
	 *
	 * @param $index
	 *
	 * @return mixed|null
	 */
	public static function _g($index){
		$var = $_GET[$index];
		if(!self::isEmpty($var)){
			return $var;
		} else {
			return NULL;
		}
	}
}