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

    public function displayLogs(): void
    {
        $logs = $this->getRecentLogs(10);
        $this->printLogs($logs);
    }

    private function getRecentLogs(int $limit)
    {
        return $this->collection->find([], [
            'sort' => ['created_at' => -1],
            'limit' => $limit
        ]);
    }

    private function printLogs(iterable $logs): void
    {
        echo PHP_EOL . "--- Last $logs->limit Logs ---" . PHP_EOL;
        foreach ($logs as $log) {
            echo "[{$log['type']}] {$log['operation']} - {$log['message']}" . PHP_EOL;
        }
    }

    public function clearLogs(): void
    {
        $result = $this->collection->deleteMany([]);
        echo "Logs cleared: {$result->getDeletedCount()} deleted." . PHP_EOL;
    }


    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

}
