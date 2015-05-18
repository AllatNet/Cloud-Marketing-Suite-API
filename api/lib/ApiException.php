<?php
/**
 * Created by PhpStorm.
 * User: Christian Höfer
 * Date: 18.05.2015
 * Time: 14:52
 */

namespace loci\api\lib;


class ApiException extends \Exception{

	public $statusCode;

	public function __construct($status, $message = null, $code = 0, \Exception $previous = null) {
		$this->statusCode = $status;
		parent::__construct($message, $code, $previous);
	}
}