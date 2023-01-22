<?php

use core\Application;

class m0001_initial {

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users_gorod ( 
            USERID NUMBER, 
            USERNAME VARCHAR(50),
            HASH VARCHAR(100),
            SALT VARCHAR(10),
            ACTIVATIONID NUMBER,
            PHONENUMBER VARCHAR(15),
            EMAIL VARCHAR(100),
            STATUS VARCHAR(10),
            ONETIMESALT VARCHAR(10),
            ONETIMEHASH VARCHAR(100),
            ONETIMEEXPIRED TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRELOADTOKEN VARCHAR(100),
            DISABLED NUMBER DEFAULT 0,
            TITLE VARCHAR(150),
            PAYMENTGATEWAY VARCHAR(50) DEFAULT 'tranzware',
            CREATED DATE DEFAULT NOW()
    ) ENGINE=InnoDB;";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS users_gorod;";
        Application::$app->db->pdo->exec($sql);
    }

}