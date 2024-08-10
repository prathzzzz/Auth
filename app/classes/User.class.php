<?php
class User {
    private static string $table = 'users';

    public static  function migrate(DatabaseConnection $connection, bool $debug) {
        $queryBuilder = new QueryBuilder($connection, $debug);
        $table = self::$table;
        $ddl = <<<DDL
            CREATE TABLE IF NOT EXISTS {$table} (
                id INT(11) PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(255),
                email VARCHAR(255),
                password VARCHAR(255)
            );
            DDL;

            $queryBuilder->executeDDL($ddl);
    }

    public static function create (DatabaseConnection $connection, array $data): bool {
        $queryBuilder = new QueryBuilder($connection, AppConfig::getInstance()->APP_DEBUG);
        if(!isset($data['password'])) {
            return false;
        }
        $data['password'] = Hash::make($data['password']);
        return  $queryBuilder->table(self::$table)
                                ->insert($data);
    }

    public static function update (DatabaseConnection $connection,int $userId, array $data): bool {
        $queryBuilder = new QueryBuilder($connection, AppConfig::getInstance()->APP_DEBUG);
        $data['password'] = Hash::make($data['password']);
        return  $queryBuilder->table(self::$table)
                                ->where('id','=',$userId)
                                ->update($data);
    }
    public static function findByUsername(DatabaseConnection $connection, string $username): array|bool {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        $dbUser = $queryBuilder->table(self::$table)
        ->where('username','=',$username)
        ->first();

        return $dbUser;
    }

    public static function findByEmail(DatabaseConnection $connection, string $email): array|bool {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        $dbUser = $queryBuilder->table(self::$table)
        ->where('email','=',$email)
        ->first();

        return $dbUser;
    }

    public static function find(DatabaseConnection $connection, int $id ): array|bool {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        $dbUser = $queryBuilder->table(self::$table)
        ->where('id','=',$id)
        ->first();

        return $dbUser;
    }


}