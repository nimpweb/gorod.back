<?php 

namespace app\models;

use core\DBModel;
use core\Helper;

class ServiceBind extends DBModel {

    private int $id = 0;
    public string $userId = '';
    public string $srvnum = '';
    public string $account = '';
    public bool $active = false;

    public function getId() {
        return $this->id;
    }

    protected function setId(int $value) {
        $this->id = $value;
    }

    public static function tableName(): string {
        return 'bindservice';
    }

    public static function primaryKey(): string {
        return 'id';
    }

    public function attributes(): array {
        return [];
    }

    public function labels(): array {
        return [];
    }

    public function rules(): array {
        return [];
    }

    public function getUser() {
        $user = new User($this->userId);
        $user->prepareInstance();
        return $user;
    }

    public static function byUserId(int $userId) {
        
        $sql = "SELECT bs.srvnum, bs.account, bs.ACTIVE, s.srvname, s.srvtype, s.prvname 
        FROM bindservice bs LEFT JOIN service s ON (bs.srvnum=s.srvnum)
        WHERE bs.userid=:userid AND s.available=:available";

        $result = self::select($sql, ['userid' => $userId, 'available' => 1]);
        if ($result && is_array($result)) return $result;
        return [];
    }


}