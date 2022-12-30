<?php

use core\Application;

class m0001_initial {

    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS users ( 
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    email varchar(255) NOT NULL,
                    firstname varchar(255) NOT NULL,
                    lastname varchar(255) NOT NULL,
                    status tinyint NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB;";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() {
        $sql = "DROP TABLE IF EXISTS users;";
        Application::$app->db->pdo->exec($sql);
    }

}