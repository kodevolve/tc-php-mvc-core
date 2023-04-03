<?php
namespace daviscodedev\phpmvc;

class Session {

    protected const FLASH_KEY = 'flash_messages';


    public function __construct() {
        session_start();
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flash_messages as $key => &$flash_message) {
            // mark to be removed
            $flash_message['remove'] =  true;
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;


    }
    public function __destruct() {
        // iterate over marked to be removed and remove
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($flash_messages as $key => &$flash_message) {
            // mark to be removed
            if($flash_message['remove']) {
                unset($flash_messages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;
        // echo '<pre><br><br><br><br><br><br>';
        // print_r($flash_messages);
        // echo '</pre>';
    }

    public function set_flash($key, $message) {
        $_SESSION[self::FLASH_KEY][$key] = [
            'removed' => false,
            'value' => $message,
        ];
    }
    public function get_flash($key) {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function get($key) {return $_SESSION[$key] ?? false;}
    public function set($key, $value) {$_SESSION[$key] = $value;}
    public function remove($key) {unset($_SESSION[$key]);}

}