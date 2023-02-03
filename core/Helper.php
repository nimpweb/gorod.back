<?php

namespace core;

class Helper {

    public static function dump($data, $die = false) {
        // echo "<pre>".var_dump($data)."</pre>";
        echo var_dump($data);
        if ($die) exit;
    }

    public static function debug($data, $die = false) {
        echo "<pre>".print_r($data, true)."</pre>";
        if ($die) exit;
    }


}