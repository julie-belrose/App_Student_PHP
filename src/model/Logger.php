<?php

namespace src\model;

use MongoDB\Collection;
use MongoDB\BSON\UTCDateTime;

class Logger
{
    private Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function log(LogType $type, string $operation, string $message): void
    {
        $this->validateOperation($operation);
        $this->saveLog($type, $operation, $message);
    }

    private function validateOperation(string $operation): void
    {
        if (trim($operation) === '') {
            throw new \InvalidArgumentException("Operation name cannot be empty.");
        }
    }

    private function saveLog(LogType $type, string $operation, string $message): void
    {
        $this->collection->insertOne([
            'type' => $type->value, // Convert enum to string
            'operation' => $operation,
            'message' => $message,
            'created_at' => new UTCDateTime()
        ]);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }
}
