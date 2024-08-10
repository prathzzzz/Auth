<?php

class ErrorHandler {
    protected array $errorBag;

    public function __construct() {
        $this->errorBag = array();
    }

    public function addError(string $key, string $errorMessage): void {
        $this->errorBag[$key][] = $errorMessage;
    }

    public function hasErrors(): bool {
        return count($this->errorBag);
    }
    public function has(string $key): bool {
        return isset($this->errorBag[$key]);
    }

    public function all(string $key = null): mixed {
        return $key == null ? $this->errorBag : $this->errorBag[$key];
    }

    public function first(string $key): string |bool {
        return isset($this->errorBag[$key]) ? $this->errorBag[$key][0] : false;
    }
}