<?php 

namespace app\models;

use core\DBModel;

class Service extends DBModel {

    public int $srvnum = 0;
    public string $srvtype = '';
    public bool $available = false;
    public int $groupId = 0;
    public string $srvname = '';
    public string $prvname = '';
    public string $phone = '';

    public static function tableName(): string {
        return 'service';
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function attributes(): array {
        return ['srvnum', 'srvtype', 'available', 'groupid', 'srvname', 'prvname', 'phone'];
    }

    public function labels(): array {
        return [];
    }

    public function rules(): array {
        return [];
    }

    public static function list() : array {
        return self::find([]);
    }

    public static function title(int $code) {
        $result = self::select('SELECT title FROM '.self::tableName().' WHERE id=?id', ['id' => $code]);
        if ($result && is_array($result)) return $result[0];
        return '';
    }


}