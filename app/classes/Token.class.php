<?php
class Token {
    private static string $table = 'tokens';

    protected const TYPES= [
        'REMEMBER_ME' => 0,
        'FORGOT_PASSWORD'=>1
    ];
    protected const EXPIRY = [
        self::TYPES['REMEMBER_ME'] => '+30 minutes',
        self::TYPES['FORGOT_PASSWORD'] => '+10 minutes'
    ];

    public static  function migrate(DatabaseConnection $connection, bool $debug) {
        $queryBuilder = new QueryBuilder($connection, $debug);
        $table = self::$table;
        $ddl = <<<DDL
            CREATE TABLE IF NOT EXISTS {$table} (
                id INT(11) PRIMARY KEY AUTO_INCREMENT,
                token VARCHAR(255),
                user_id int(11),
                expires_at DATETIME,
                type tinyint
            );
            DDL;

            $queryBuilder->executeDDL($ddl);
    }
    public static function createRememberMeToken(DatabaseConnection $connection, int $userId) {
        return self::create($connection,$userId,self::TYPES['REMEMBER_ME']);
    }
    public static function createForgotPasswordToken(DatabaseConnection $connection, int $userId) {
        return self::create($connection,$userId,self::TYPES['FORGOT_PASSWORD']);
    }


    public static function create (DatabaseConnection $connection,int $userId, int $type): mixed {
        $existingToken = self::getValidExistingToken($connection,$userId,$type);
        if($existingToken) {
            return $existingToken;
        }
        $queryBuilder = new QueryBuilder($connection, AppConfig::getInstance()->APP_DEBUG);
        $data['token'] = Hash::generateToken($userId,$type);

        $currentDateTime = date('Y-m-d H:i:s');
        $expiryTime = date('Y/m/d H:i:s',strtotime($currentDateTime . self::EXPIRY[$type]));

        $data['expires_at'] = $expiryTime;
        $data['user_id'] = $userId;
        $data['type'] = $type;

        return  $queryBuilder->table(self::$table)
                                ->insert($data) ? $data  : false;
    }

    public static function getValidExistingToken(DatabaseConnection $connection,int $userId, int $type) {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        $currentDateTime = date('Y-m-d H:i:s');

        return $queryBuilder->table(self::$table)
                            ->where('user_id','=',$userId)
                            ->where('type','=',$type)
                            ->where('expires_at','>',$currentDateTime)
                            ->first();
    }
    public static function isValid(DatabaseConnection $connection,string $token): array|bool {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        $currentDateTime = date('Y-m-d H:i:s');
        
        return $queryBuilder->table(self::$table)
                            ->where('token','=', $token)
                            ->where('expires_at','>', $currentDateTime)
                            ->first();
    }
    public static function deleteForgotPasswordToken(DatabaseConnection $connection,int $userId): bool {
        return self::deleteTokens($connection,$userId,self::TYPES['FORGOT_PASSWORD']);
    }
    public static function deleteRememberMeToken(DatabaseConnection $connection,int $userId): bool {
        return self::deleteTokens($connection,$userId,self::TYPES['REMEMBER_ME']);
    }
    private static function deleteTokens(DatabaseConnection $connection,int $userId, int $type): bool {
        $queryBuilder = new QueryBuilder($connection,AppConfig::getInstance()->APP_DEBUG);
        return $queryBuilder->table(self::$table)
                            ->where('type','=',$type)
                            ->where('user_id','=',$userId)
                            ->delete();
    }
}