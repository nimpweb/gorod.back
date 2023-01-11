<?php

namespace core;

abstract class DBModel extends Model {

    abstract public static function tableName(): string;
    abstract public static function primaryKey(): string;
    abstract public function attributes(): array;

    public static function byId(int $userId) {
        $data = self::findOne(['id' => $userId]);
        if (!empty($data)) {
            $instance = new static();
            $instance->loadData($data);
            return $instance;
        }
        return false;
    }

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
        $data = static::find($where);
        return ($data) ? $data[0] : null;
    }

    public static function delete(int $id): bool {
        $tableName = static::tableName();
        $statement = self::prepare("DELETE FROM $tableName WHERE id=:id");
        $statement->bindValue(':id', $id);
        return $statement->execute();
    }

    public static function select(string $sql, array $where = []) {
        $statement = self::prepare($sql);
        if (!empty($where)) {
            foreach($where as $value) $statement->bindValue(":$value", $value);
        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    public static function execSQL(string $sql, array $values = []): bool {
        $statement = self::prepare($sql);
        if (!empty($values)) {
            foreach($values as $value) $statement->bindValue(":$value", $value);
        }
        return $statement->execute();
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

    public function update() {
        $tableName = static::tableName();
        $attributes = $this->attributes();
    }

    public function __get(string $name) {
        if (in_array($name, $this->attributes())) {
            return $this->{$name};
        }
        return false;
    }

    public function toArray(): array {
        $arr = [];
        foreach($this->attributes() as $key => $value) {
            $arr[$key] = $value;
        }
        return $arr;
    }    

    public static function prepare($sql) {
        return Application::$app->db->pdo->prepare($sql);
    }

    public static function getLastInsertId() {
        return Application::$app->db->pdo->lastInsertId();
    }

}