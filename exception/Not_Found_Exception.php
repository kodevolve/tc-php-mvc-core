<?php
namespace daviscodedev\phpmvc\exception;

use Exception;

class Not_Found_Exception extends Exception {
    protected $message = 'Page not found';
    protected $code = 404;

}