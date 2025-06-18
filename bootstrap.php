<?php
use MongoDB\Client;
use src\repository\StudentRepository;
use src\service\StudentService;
use src\model\Log;

function initApp(): array
{
    $client = new Client("mongodb://localhost:27017");
    $db = $client->school;

    $studentCollection = $db->students;
    $logCollection = $db->logs;

    $loggerService = new LogService($logCollection);
    $studentRepo = new StudentRepository($studentCollection);
    $studentService = new StudentService($studentRepo, $logger);

    return [
        'studentService' => $studentService,
        'loggerService' => $loggerService
    ];

}
