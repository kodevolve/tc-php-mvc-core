<?php
namespace daviscodedev\phpmvc\form;

use daviscodedev\phpmvc\Model;

class Form {
    public static function begin($action, $method) {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form;
    }

    public static function end() {
        echo '</form>';
    }
    public function field(Model $model, $attribute) {
        return new Input_Field($model, $attribute);
    }
}