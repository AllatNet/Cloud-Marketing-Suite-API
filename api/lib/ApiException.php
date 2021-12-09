<?php
/**
 * Loci - Cloud Marketing Suite API
 * Lizenz: https://www.apache.org/licenses/LICENSE-2.0
 * Version: 1.3.0
 * Author: AllatNet Internetsysteme
 * Link: https://fb-sites.com/api/
 */
namespace loci\api\lib;

class ApiException extends \Exception{

	public $statusCode;

	public function __construct($status, $message = null, $code = 0, \Exception $previous = null) {
		$this->statusCode = $status;
		parent::__construct($message, $code, $previous);
	}
}