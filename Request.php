<?php
namespace daviscodedev\phpmvc;

class Request {


    public function get_path() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }
    public function method() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function is_get() {
        return $this->method() === 'get';
    }
    public function is_post() {
        return $this->method() === 'post';
    }

    public function get_body() {
        $body = [];
        if($this->method() === 'get') {
            foreach($_GET as $key=>$value) {
                $body[$key] = filter_input(\INPUT_GET, $key, \FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->method() === 'post') {
            foreach($_POST as $key=>$value) {
                $body[$key] = filter_input(INPUT_POST, $key, \FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        // echo '<pre>';
        // // print_r($_POST);
        // print_r($body);
        // echo '</pre>';
        // exit;

        return $body;
    }

}