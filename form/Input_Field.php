<?php
namespace app\core\form;

use app\core\Model;

class Input_Field extends Base_Field {

    public static $errors = [];
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';

    public string $type = '';
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute) {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }


    public function password_field() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function render_input(): string {
        return sprintf('<input type="%s" id="%s" name="%s" value="%s" class="form-control%s">',
        $this->type,
        $this->attribute,
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->has_error($this->attribute) ? ' is-invalid' : '',
        );
    }
}