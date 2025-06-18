<?php
function handleCli(array $services): void
{
    $studentService = $services['studentService'];
    $logger = $services['logger'];

    while (true) {
        DisplayMenu();
        $input = readline("Choose an option: ");

        match ($input) {
            "1" => $studentService->displayStudents(),
            "2" => $studentService->createStudent(),
            "3" => $studentService->editStudent(),
            "4" => $studentService->deleteStudent(),
            "5" => $studentService->searchStudentsByIdentity(),
            "6" => $logger->displayLogs(),
            "7" => $logger->clearLogs(),
            "0" => exit("Goodbye!\n"),
            default => print("Invalid input.\n")
        };

        echo "\n--- Press Enter to continue ---\n";
        readline();
    }
}
