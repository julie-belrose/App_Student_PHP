<?php

namespace src\repository;

use MongoDB\Collection;
use src\mapper\LogMapper;
use src\model\Log;

readonly class LogRepository
{
    public function __construct(private Collection $collection) {}

    public function save(Log $log): void
    {
        $doc = LogMapper::logToDocument($log);
        $this->collection->insertOne($doc);
    }

    public function findRecent(int $limit = 10): array
    {
        $cursor = $this->collection->find([], [
            'sort' => ['created_at' => -1],
            'limit' => $limit
        ]);

        $logs = [];
        foreach ($cursor as $doc) {
            $logs[] = LogMapper::documentToLog($doc);
        }

        return $logs;
    }

    public function clear(): int
    {
        $result = $this->collection->deleteMany([]);
        return $result->getDeletedCount();
    }
}