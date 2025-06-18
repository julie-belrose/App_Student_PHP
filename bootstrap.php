<?php
use MongoDB\Client;
use src\repository\StudentRepository;
use src\service\StudentService;
use src\model\Logger;

function initApp(): array
{
    $client = new Client("mongodb://localhost:27017");
    $db = $client->school;

    $studentRepo = new StudentRepository($db->students);
    $logger = new Logger($db->logs);
    $service = new StudentService($studentRepo, $logger);

    return [$service, $logger];
}
