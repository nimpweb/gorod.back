<?php

namespace core\form;

use core\Model;

class Field {

    public Model $model;
    public string $attribute;

    public function __construct(Model $model, $attribute) {
        $this->model = $model;
        $this->attribute = $attribute;
    }


    public function __toString() {
        return sprintf('
            <div class="form-group">
                <label>%s</label>
                <input type="text" name="%s" class="form-control%s" value="%s" />
                <div class="invalid-feedback">%s</div>
            </div>
        ', 
            $this->model->getLabel($this->attribute), 
            $this->attribute, 
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}, 
            $this->model->getFirstError($this->attribute)
        );
        
    }
}