<?php
function handleCli(array $services): void
{
    $studentService = $services['studentService'];
    $loggerService = $services['loggerService'];

    while (true) {
        displayMenu();
        $input = readline("Choose an option: ");

        match ($input) {
            "1" => $studentService->displayStudents(),
            "2" => $studentService->createStudent(),
            "3" => $studentService->editStudent(),
            "4" => $studentService->deleteStudent(),
            "5" => $studentService->searchStudentsByIdentity(),
            "6" => $loggerService->displayLogs(),
            "7" => $loggerService->clearLogs(),
            "0" => exit("Goodbye!" . PHP_EOL),
            default => print("Invalid input" . PHP_EOL),
        };

        echo "\n--- Press Enter to continue ---\n";
        readline();
    }
}
