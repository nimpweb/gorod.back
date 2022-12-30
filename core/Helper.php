<?php

namespace core;

class Helper {

    public static function debug($data, $die = false) {
        // echo "<pre>".var_dump($data)."</pre>";
        echo var_dump($data);
        if ($die) exit;
    }

}