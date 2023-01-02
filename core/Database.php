<?php

namespace core;

class Database {

    public \PDO $pdo;

    public function __construct(array $config) {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }

    /** Migrations block...  */
    public function applyMigrations() {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toAppliedMigrations = array_diff($files, $appliedMigrations);
        $newMigrations = [];
        foreach ($toAppliedMigrations as $migration) {
            if ($migration === '.' || $migration === '..') continue;
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $this->migrationsLog("Appliying migration $migration");
            $instance = new $className();
            $instance->up();
            $this->migrationsLog("Done migration $migration");
            $newMigrations[] = $migration; 
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->migrationsLog("All migrations are done...");
        }
    }

    /**
     *  array $migrations
     *  save all migrations in database
     */
    private function saveMigrations(array $migrations) {
        $data = implode(',', array_map(function($m) {return "('$m')"; }, $migrations));
        $statement = $this->pdo->prepare('INSERT INTO migrations(migration) VALUES '.$data);
        $statement->execute();
    }

    /**
     *  create a migration table if not existing
     */
    private function createMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations ( id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB";
        $this->pdo->exec($sql);
    }

    /**
     *  get all applied migrations
     */
    private function getAppliedMigrations() {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /** print a migration log */
    private function migrationsLog($message) {
        echo "[".date("Y-m-d H:i:s")."] - " . $message.PHP_EOL;
    }

}