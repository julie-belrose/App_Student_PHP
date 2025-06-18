<?php
function handleUserInput(StudentService $service, Logger $logger): void
{
    while (true) {
        displayMenu();
        $input = readline("Choose an option: ");

        match ($input) {
            "1" => $service->displayStudents(),
            "2" => $service->createStudent(),
            "3" => $service->editStudent(),
            "4" => $service->deleteStudent(),
            "5" => $service->searchStudentsByIdentity(),
            "6" => exit("Goodbye!\n"),
            "7" => $logger->displayLogs(),
            "8" => $logger->clearLogs(),
            default => print("Invalid input.\n"),
        };

        echo "\n--- Press Enter to continue ---\n";
        readline();
    }
}
