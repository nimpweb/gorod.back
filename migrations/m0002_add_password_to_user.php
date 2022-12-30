<?php

use core\Application;

class m0002_add_password_to_user {

    public function up() {
        $sql = "ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL;";
        Application::$app->db->pdo->exec($sql);
    }

    public function down() {
        $sql = "ALTER TABLE users DROP COLUMN password;";
        Application::$app->db->pdo->exec($sql);
    }

}