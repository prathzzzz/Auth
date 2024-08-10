<?php
define('USER_KEY','logged_in_user'); //c ke #define jaisa hai 
class Auth {
    public static function login(DatabaseConnection $connection, string $username, string $password): bool {
        $dbUser = User::findByUsername($connection,$username);
        if($dbUser) {
            if(Hash::verify($password,$dbUser['password'])) {
                self::setLoggedInUser($dbUser);
                return true;
            }
        }
        return false;
    }

    public static function setLoggedInUser(array $user): void {
        $_SESSION[USER_KEY] = $user;
    }

    public static function logout(): bool {
        if(isset($_SESSION[USER_KEY])) {
            unset($_SESSION[USER_KEY]);
            return true;
        }
        return false;
    }

    public static function check(): bool {
        return isset($_SESSION[USER_KEY]);
    }

    public static function user(): array|null {
        if(isset($_SESSION[USER_KEY])) {
            return $_SESSION[USER_KEY];
        }
        return null;
    }
} 