<?php

class DatabaseConnection {
    private ?PDO $pdo = null;

    public function __construct(private AppConfig $config) {
        $this->connect();
    }

    private function connect(): void {
        try {
            $dsn=  "mysql:host={$this->config->DB_HOST};dbname={$this->config->DB_NAME}";
            $this->pdo = new PDO($dsn, $this->config->DB_USERNAME, $this->config->DB_PASSWORD);

            if($this->config->APP_DEBUG) {
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }catch(PDOException $e) {
            die(
                $this->config->APP_DEBUG ? $e->getMessage() : 'Unable to Connect Database!'
            );
        }
    }

    public function getPdo() : ?PDO{
        return $this->pdo;
    }
}