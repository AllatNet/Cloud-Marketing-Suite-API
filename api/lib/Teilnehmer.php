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
 * Class Teilnehmer
 * @package loci\api\lib
 *
 *
 * @property string $id
 * @property string $anrede
 * @property string $titel
 * @property string $vorname
 * @property string $nachname
 * @property string $strasse
 * @property string $plz
 * @property string $ort
 * @property string $land
 * @property string $geburtstag
 * @property string $email
 * @property string $optin
 */
class Teilnehmer
{

    /** @var array "Attribute des Partners" */
    private $_attributes = [];

    /**
     * @var string "API-Schlüssel"
     */
    private $_token;

    /**
     * Holt eine Attribute eines Teilnehmers
     *
     * @param string $name "Name der Attribute"
     *
     * @return string|array "Liefert eine Attribute"
     */
    public function __get($name) {
        switch ($name) {
            case 'attributes':
                return $this->_attributes;
                break;
            case 'id':
                return $this->_attributes['_id'];
                break;
            case 'token':
                return $this->_token;
            default:
                if (isset($this->_attributes[$name])) {
                    return $this->_attributes[$name];
                }
        }
        return '';
    }

    /**
     * Setzt eine Attribute eines Teilnehmers
     *
     * @param string $name "Name der Attribute"
     * @param mixed $value "Wert der Attribute"
     *
     * @return bool "Attribute erfolgreich geändert"
     */
    public function __set($name, $value) {
        switch ($name) {
            case 'attributes':
                if (!is_array($value))
                    $value = (array)$value;
                $this->_attributes = $value;
                break;
            case 'token':
                if (empty($this->_token))
                    $this->_token = NULL;
                $this->_token = $value;
                break;
            default:
                $this->_attributes[$name] = $value;
        }
        return true;
    }
}