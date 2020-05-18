<?php
namespace database;

class DatabaseNotExistException extends DatabaseException
{
    public function __construct($message, $code = 0, DatabaseException $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}";
    }
}

