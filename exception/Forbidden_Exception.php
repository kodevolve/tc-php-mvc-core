<?php
namespace app\core\exception;

use Exception;

class Forbidden_Exception extends \Exception {

    protected $message = "You don't have permission to access this page";
    protected $code = 403;
}