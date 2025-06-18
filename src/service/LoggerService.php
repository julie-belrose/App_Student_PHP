<?php

namespace src\service;

use src\enum\LogType;
use src\model\Log;
use src\repository\LogRepository;

readonly class LoggerService
{
    public function __construct(private LogRepository $repository) {}

    public function log(LogType $type, string $operation, string $message): void
    {
        $operation = trim($operation);
        if ($operation === '') {
            throw new \InvalidArgumentException("Operation name cannot be empty.");
        }

        $this->repository->save(new Log($type->value, $operation, $message));
    }

    public function displayLogs(): void
    {
        echo PHP_EOL . "--- Last Logs ---" . PHP_EOL;
        foreach ($this->repository->findRecent() as $log) {
            echo "[{$log->type}] {$log->operation} - {$log->message}" . PHP_EOL;
        }
    }

    public function clearLogs(): void
    {
        echo "Logs cleared: {$this->repository->clear()} deleted." . PHP_EOL;
    }
}
