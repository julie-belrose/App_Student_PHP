<?php
namespace src\model;

use MongoDB\BSON\UTCDateTime;

class Log
{
    public function __construct(
        public string       $type,
        public string       $operation,
        public string       $message,
        public ?UTCDateTime $createdAt = null
    )
    {
    }
}