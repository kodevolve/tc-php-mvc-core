<?php

namespace daviscodedev\phpmvc\form;

class Textarea_Field extends Base_Field {


    public function render_input(): string {
        return sprintf('<textarea name="%s" class="form-control">%s</textarea>',
        $this->attribute,
        $this->model->has_error($this->attribute) ? ' is-invalid' : '',
        $this->model->{$this->attribute},
        );
    }
}