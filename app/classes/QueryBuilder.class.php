<?php

class QueryBuilder {
    protected ?PDO $pdo = null;
    protected string $table = '';
    protected ?PDOStatement $stmt = null;
    protected bool $debug;
    protected array $whereConditions = [];
    protected array $bindings = [];
    protected ?string $sqlStatement = null;

    public function __construct(DatabaseConnection $connection, bool $debug) {
        $this->pdo = $connection->getPdo();
        $this->debug = $debug;
    }

    public function executeDDL(string $sql): bool {
        try {
            return $this->pdo->exec($sql) !== false;
        }catch(PDOException $e) {
            if($this->debug) {
                dd($e->getMessage());
            }
            dd("Error with Dataase! Connect with Admin!");
        }
    }

    public function table (string $table): self {
        $this->table = $table;
        return $this;
    }

    public function delete(): bool {
        $this->sqlStatement = "DELETE FROM `{$this->table}`" . $this->bindWhereClause();
        $this->stmt = $this->pdo->prepare($this->sqlStatement);
        //upar wale stmt me placeholder hai
        //bindings array me actual field and uska value hai
        return $this->stmt->execute($this->bindings);
    }

    public function update(array $data): bool {
        $setClause = implode(", ",array_map(function($field) {
            return "`$field` = :update$field";
        },array_keys($data)));


        $this->sqlStatement  = "UPDATE {$this->table} SET 
        {$setClause}" . $this->bindWhereClause();

        $this->stmt = $this->pdo->prepare($this->sqlStatement);
        $this->bindValues($data);
        return $this->stmt->execute($this->bindings);
    }

    protected function bindValues(array $data): void {
        foreach($data as $key =>  $value) {
            $this->bindings["update$key"] = $value;
        }
    }

    public function insert(array $data): bool {
        $keys = array_keys($data);
        $fields = "`" . implode("`, `", $keys) . "`";
        $placeholders = ":".implode(", :",$keys);
        $sql = "INSERT INTO {$this->table} ({$fields})  VALUES ({$placeholders})";

        $this->stmt = $this->pdo->prepare($sql);

        return $this->stmt->execute($data);
    }

    public function where(string $field, string $operator, string $value): self {
        $this->whereConditions[] = "`$field` $operator  :$field";
        $this->bindings[$field] = $value;
        return $this;
    }

    public function select(string $fields=  "*"): self {
        if($this->sqlStatement === null) {
            $this->sqlStatement = "SELECT $fields FROM `{$this->table}`" . $this->bindWhereClause();
        }
        return $this;
    }

    public function first(): array|bool {
        $this->limit(0,1);
        $data = $this->get();
        return !empty($data)  ? $data[0] : false;
    }
    public function get(): array {
        $this->select();
        $this->stmt = $this->pdo->prepare($this->sqlStatement);
        $this->stmt->execute($this->bindings);

        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->reset();

        return $data;
    }

    public function count() : int {
        $this->select("COUNT(*) as count");
        $data = $this->get();
        return $data[0]['count'];
    }

    public function limit(int $offset, int $limit): self {
        $this->select();
        $this->sqlStatement .= " LIMIT $offset, $limit";
        return $this;
    }


    public function exists(array $data): bool {
        foreach($data as $key=>$value) {
            $this->where($key,"=",$value);
        }
        return $this->count();
    }

    protected function reset(): void {
        $this->sqlStatement = null;
        $this->stmt = null;
        $this->whereConditions = [];
        $this->bindings = [];
    }

    protected function bindWhereClause(): string {
        if(empty($this->whereConditions)) {
            return '';
        }
        return " WHERE " . implode(' AND ',$this->whereConditions);
    }
}