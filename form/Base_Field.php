<?php
namespace daviscodedev\phpmvc\form;

use daviscodedev\phpmvc\Model;

abstract class Base_Field {


    public static $errors = [];
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';


    public Model $model;
    public string $attribute;


    abstract public function render_input(): string;



    public function __construct(Model $model, string $attribute) {
        $this->model = $model;
        $this->attribute = $attribute;
    }


    public function __toString() {
        self::$errors = $this->model->has_error($this->attribute);
        $return = sprintf('
            <div class="form-group">
                <label for="%s">%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ',
        $this->attribute,
        $this->model->get_label($this->attribute),
        $this->render_input(),
        $this->model->get_first_error($this->attribute),
        );
        return $return;

    }
}