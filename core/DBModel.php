<?php

namespace core;

abstract class DBModel extends Model {

    abstract public static function tableName(): string;
    abstract public static function primaryKey(): string;
    abstract public function attributes(): array;

    public static function find(array $where = []): array {
        $tableName = static::tableName();
        $attributes = array_keys($where);

        $sql = "SELECT * FROM $tableName";
        if (!empty($where)) {
            $sql .= " WHERE ".implode(" AND ", array_map(function($attribute) {  return "$attribute = :$attribute"; }, $attributes));
        }
        $statement = self::prepare($sql);
        foreach($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public static function findOne(array $where = []) {
        return static::find($where)[0];
        // $tableName = static::tableName();
        // $attributes = array_keys($where);

        // $sql = "SELECT * FROM $tableName";
        // if (!empty($where)) {
        //     $sql .= " WHERE ".implode(" AND ", array_map(function($attribute) {  return "$attribute = :$attribute"; }, $attributes));
        // }
        // $statement = self::prepare($sql);
        // foreach($where as $key => $item) {
        //     $statement->bindValue(":$key", $item);
        // }
        // $statement->execute();
        // return $statement->fetchObject(static::class);
    }
    
    public function insert() {
        $tableName = static::tableName();
        $attributes = $this->attributes();
        $params = array_map(function($a) { return ":$a"; }, $attributes);
        $statement = self::prepare("INSERT INTO $tableName (".implode(",", $attributes).") VALUES (".implode(",", $params).")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return self::getLastInsertId();
    }

    public static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

    public static function getLastInsertId() {
        return Application::$app->db->pdo->lastInsertId();
    }

}