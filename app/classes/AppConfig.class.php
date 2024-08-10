<?php
class AppConfig {
    private static $instance = null;
    public string $APP_NAME;
    public string $APP_URL;
    public bool $APP_DEBUG = false;


    public string $DB_NAME;
    public string $DB_HOST;
    public string $DB_USERNAME;
    public string $DB_PASSWORD;

    public string $MAIL_HOST;
    public string $MAIL_PORT;
    public string $MAIL_USERNAME;
    public string $MAIL_PASSWORD;
    public string $MAIL_FROM_ADDRESS;




    private function __construct() {

    }

    public static function getInstance(string $rootPath ="./"):self {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        self::reloadEnv($rootPath);
        return self::$instance;
    }

    private static function reloadEnv(string $rootPath): void {
        try {
            $dotEnv = Dotenv\Dotenv::createUnsafeImmutable($rootPath);
            $dotEnv->load();

            $dotEnv->required(['APP_DEBUG']);
            self::$instance->APP_DEBUG = getenv("APP_DEBUG") !== "false";

            $dotEnv->required(['DB_NAME','DB_USERNAME','DB_PASSWORD','DB_HOST','MAIL_HOST','MAIL_PORT','MAIL_USERNAME','MAIL_PASSWORD','MAIL_FROM_ADDRESS','APP_NAME','APP_URL']);
            self::$instance->DB_NAME = getenv("DB_NAME");
            self::$instance->DB_HOST = getenv("DB_HOST");
            self::$instance->DB_USERNAME = getenv("DB_USERNAME");
            self::$instance->DB_PASSWORD = getenv("DB_PASSWORD");

            self::$instance->MAIL_HOST = getenv("MAIL_HOST");
            self::$instance->MAIL_PORT = getenv("MAIL_PORT");
            self::$instance->MAIL_USERNAME = getenv("MAIL_USERNAME");
            self::$instance->MAIL_PASSWORD = getenv("MAIL_PASSWORD");
            self::$instance->MAIL_FROM_ADDRESS = getenv("MAIL_FROM_ADDRESS");


            self::$instance->APP_NAME = getenv("APP_NAME");
            self::$instance->APP_URL = getenv("APP_URL");

        }catch(Exception $e) {
            die(
                self::$instance->APP_DEBUG ?  $e->getMessage() : 'Unable to find config!'
            );
        }
    }
}