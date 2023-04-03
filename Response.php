<?php
namespace daviscodedev\phpmvc;

class Response {


    public function set_status_code(int $code) {
        return http_response_code($code);
    }

    public function redirect(string $url) {
        header('Location: '.$url);
        exit;
    }
}