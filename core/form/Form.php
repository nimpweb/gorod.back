<?php

namespace core\form;

use core\Model;

class Form {

    public static function begin($action, $method = "post") {
        echo '<form action="'.$action.'" method="'.$method.'">';
        return new Form();
    }

    public static function end() {
        return '</form>';
    }

    public function field(Model $model, $attribute) {
        return new Field($model, $attribute);
    }

}