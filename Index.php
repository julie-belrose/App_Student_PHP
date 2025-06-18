<?php

require './vendor/autoload.php';

use MongoDB\Client;
use src\repository\StudentRepository;
use src\service\StudentService;
use src\model\Logger;

function menu(): void
{
    echo "
       _             _ _             _
   ___| |_ _   _  __| (_) __ _ _ __ | |_ ___
  / _ \ __| | | |/ _` | |/ _` | '_ \| __/ __|
 |  __/ |_| |_| | (_| | | (_| | | | | |_\__ \\
  \___|\__|\__,_|\__,_|_|\__,_|_| |_|\__|___/" . PHP_EOL;

    echo "1. Display students
2. Create a student
3. Edit a student
4. Delete a student
5. Search by name or surname
6. Exit
7. Display last 10 logs
8. Clear logs" . PHP_EOL;
}

try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->school;
    $collection = $db->student;

    // Main collections
    $students = $db->students;
    $logs = $db->logs;

    echo "Connected to MongoDB successfully!" . PHP_EOL;

    $logger = new Logger($logs);
    $studentRepo = new StudentRepository($students);
    $studentService = new StudentService($studentRepo, $logger);

    while (true) {
        menu();
        $input = readline("Choose an option: ");
        match ($input) {
            "1" => $studentService->displayStudents(),
            "2" => $studentService->createStudent(),
            "3" => $studentService->editStudent(),
            "4" => $studentService->deleteStudent(),
            "5" => $studentService->searchStudentsByIdentity(),
            "6" => exit("Goodbye!\n"),
            "7" => $logger->displayLogs(),
            "8" => $logger->clearLogs(),
            default => print("Invalid input.\n"),
        };

        echo "\n--- Press Enter to continue ---\n";
        readline();
    }

} catch (Exception $e) {
    echo "MongoDB Error: " . $e->getMessage(), PHP_EOL;
}
