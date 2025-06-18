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

    public function log(string $level, string $message, array $context = []): void
    {
        $this->collection->insertOne([
            'level' => $level,
            'message' => $message,
            'context' => $context,
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
