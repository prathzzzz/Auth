<?php

class Hash {
    public static function make(string $plainText) {
        return password_hash($plainText, PASSWORD_BCRYPT);
    }
    

    public static function verify(string $plainText, string $hash) {
        return password_verify($plainText,$hash);
    }

    public static function generateToken(int $userId=0, int $type=0) {
        return hash('sha256',"$userId".strrev($type).random_bytes(10).getCurrentTimeInMillis().strrev($userId).$type.random_bytes(20));
    }
}