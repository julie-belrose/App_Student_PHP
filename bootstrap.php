<?php
use MongoDB\Client;
use src\repository\StudentRepository;
use src\service\StudentService;
use src\model\Logger;

function initApp(): array
{
    $client = new Client("mongodb://localhost:27017");
    $db = $client->school;

    $studentCollection = $db->students;
    $logCollection = $db->logs;

    $logger = new Logger($logCollection);
    $studentRepo = new StudentRepository($studentCollection);
    $studentService = new StudentService($studentRepo, $logger);

    return [
        'studentService' => $studentService,
        'logger' => $logger
    ];

}
