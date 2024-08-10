<?php

class Validator {
    protected array $rules = ['required','minlength','maxlength','email','unique'];
    protected array $messages = [
        'required' => 'The :field field is required',
        'minlength' => 'The :field field must be a minimum of :satisfier characters.',
        'maxlength' => 'The :field field must be a maximum of :satisfier characters. ',
        'email' => 'That is not a valid email address!',
        'unique' => 'That :field is already taken'
    ];
    private QueryBuilder $queryBuilder;
    private ErrorHandler $errorHandler;
    public function __construct(DatabaseConnection $connection,bool $debug,ErrorHandler $errorHandler) {
        $this->queryBuilder = new QueryBuilder($connection, $debug);
        $this->errorHandler = $errorHandler;
    }

    public function check(array $data,array $rules): bool {
        foreach($data as $item => $valueToBeValidated) {
            if(isset($rules[$item])) {
                $this->validate($item, $valueToBeValidated, $rules[$item]);
            }
        } 
        return !$this->errorHandler->hasErrors();
    }

    public function errors(): ErrorHandler {
        return $this->errorHandler;
    }

    public function fails(): bool {
        return $this->errorHandler->hasErrors();
    }

    private function validate(string $field, mixed $value, array $rules): void {
        foreach($rules as $rule => $satisfier) {
            if(in_array($rule, $this->rules) && method_exists($this, $rule)) {
                if(!call_user_func_array([$this, $rule],[$field,$value,$satisfier])) {
                    $message  =str_replace([':field',':satisfier'],[$field,$satisfier],
                    $this->messages[$rule]) ?? 'Inavlid Validation Rule!';
                    $this->errorHandler->addError($field,$message);
                }
            }
        }
    }
    protected function required(string $field, mixed $value, mixed $satisfier):bool {
        return !empty(trim($value));
    }

    protected function minlength(string $field, mixed $value, mixed $satisfier):bool {
        return mb_strlen($value) >= $satisfier;
    }

    protected function maxlength(string $field, mixed $value, mixed $satisfier):bool {
        return mb_strlen($value) <= $satisfier;
    }
    protected function email(string $field, mixed $value, mixed $satisfier):bool {
        return (bool)filter_var($value,FILTER_VALIDATE_EMAIL);
    }
    protected function unique(string $field, mixed $value, mixed $satisfier):bool {
        $tableName = explode(".",$satisfier)[0];
        $columnName = explode(".",$satisfier)[1];

        return !$this->queryBuilder->table($tableName)
                                    ->exists([$columnName=>$value]);
    }
}