<?php

namespace core;

use DateTime;
use DateTimeImmutable;
use Firebase\JWT\JWT;


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
        $expiredDate = $beforeDate->modify("+15 minutes");
        
        $data = [
            'iat' => $beforeDate->getTimestamp(),
            'iss' => $_ENV['HOST'],
            'nbf' => $beforeDate->getTimestamp(),
            'exp' => $expiredDate,
            'user' => $user
        ];

        return JWT::encode($data, $_ENV['TOKEN_SECRET_KEY'], 'HS512');
    }

    public static function check(string $token) : array | bool {
        $key = $_ENV['TOKEN_SECRET_KEY'] ?? ''; 
        $now = new DateTimeImmutable();
        $host = $_ENV['HOST'];
        $decodedToken = JWT::decode($token, $key, ['HS512']);
        if ($decodedToken->iss !== $host || $decodedToken->nbf > $now->getTimestamp() || $decodedToken->exp < $now->getTimestamp()) {
            return false;
        }
        return $decodedToken;
    }

    public static function isValid(string $token): bool {
        return !!self::check($token);
    }

    
}