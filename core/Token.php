<?php

namespace core;

use DateTime;
use DateTimeImmutable;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;


class Token extends DBModel {

    // public string $token = "";
    // public int $user_id = null;
    // public DateTime $expiredAt = null;

    public static function tableName(): string {
        return 'token';
    }

    public static function primaryKey(): string {
        return "id";
    }
    
    public function attributes(): array {
        return ["user_id", "token", "expiredAt"];
    }

    public function rules(): array {
        return [];
    }

    public function labels(): array {
        return [];
    }

    public static function create(DBModel $user): string {
        $beforeDate = new DateTimeImmutable();
        // $expiredDate = $beforeDate->modify("+15 minutes");
        $expiredDate = $beforeDate->modify("+24 hours");
        
        $data = [
            'iat' => $beforeDate->getTimestamp(),
            'iss' => $_ENV['API_HOST'],
            'nbf' => $beforeDate->getTimestamp(),
            'exp' => $expiredDate->getTimestamp(),
            'user' => $user
        ];


        return JWT::encode($data, $_ENV['TOKEN_SECRET_KEY'], 'HS512');
    }

    public static function check(string $token) {
        $key = $_ENV['TOKEN_SECRET_KEY'] ?? ''; 
        $now = new DateTimeImmutable();
        $host = $_ENV['API_HOST'];
        try {
            $decodedToken = JWT::decode($token, new Key($key, 'HS512'));
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
        if ($decodedToken->iss !== $host || $decodedToken->nbf > $now->getTimestamp() || $decodedToken->exp < $now->getTimestamp()) {
            return ["success" => false, "message" => 'Expired of not valid'];
        }
        return ['success' => true, 'user'=>$decodedToken->user];
    }

    public static function isValid(string $token): bool {
        return !!self::check($token);
    }

    
}