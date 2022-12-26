<?php

namespace core;

class Helper {

    public static function debug($data, $die = false) {
        echo "<pre>".print_r($data, true)."</pre>";
        var_dump($data);
        if ($die) exit;
    }

}