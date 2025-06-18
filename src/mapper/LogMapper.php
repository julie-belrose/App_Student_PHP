<?php

namespace src\mapper;

use src\model\Log;
use MongoDB\BSON\UTCDateTime;

class LogMapper
{
    public static function logToDocument(Log $log): array
    {
        return [
            'type' => $log->type,
            'operation' => $log->operation,
            'message' => $log->message,
            'created_at' => $log->createdAt ?? new UTCDateTime()
        ];
    }

    public static function documentToLog(array|object $doc): Log
    {
        return new Log(
            $doc['type'],
            $doc['operation'],
            $doc['message'],
            $doc['created_at'] ?? null
        );
    }
}