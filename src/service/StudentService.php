<?php

namespace src\service;

use Exception;
use src\enum\LogType;
use src\model\Student;
use src\repository\StudentRepository;

readonly class StudentService
{
    public function __construct(
        private StudentRepository $studentRepository,
        private LogService $logger
    ) {}

    public function displayStudents(): void
    {
        try {
            $students = $this->studentRepository->findAll();
            echo "=== List of students ===\n";
            if (empty($students)) {
                echo "No students found.\n";
            }
            foreach ($students as $student) {
                echo $student . PHP_EOL;
            }
            $this->logger->log(LogType::DEBUG, 'Display', 'Displayed all students.');
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Display', 'Error displaying students: ' . $e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public function createStudent(): bool
    {
        echo "First name: ";
        $firstname = trim(readline());

        echo "Last name: ";
        $lastname = trim(readline());

        echo "Date of birth (yyyy-mm-dd): ";
        $dob = trim(readline());

        echo "Email: ";
        $email = trim(readline());

        if (empty($firstname) || empty($lastname)) {
            echo "First name and last name are required.\n";
            $this->logger->log(LogType::WARN, 'Insertion', 'Missing name or surname.');
            return false;
        }

        $student = new Student(null, $firstname, $lastname, $dob ?: null, $email ?: null);

        try {
            $this->studentRepository->save($student);
            $this->logger->log(LogType::DEBUG, 'Insertion', "Inserted student: {$student->firstname} {$student->lastname}");
            echo "Student created successfully.\n";
            return true;
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Insertion', 'Exception during save: ' . $e->getMessage());
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function editStudent(): void
    {
        echo "Student ID to edit: ";
        $id = trim(readline());

        $student = $this->studentRepository->findById($id);
        if (!$student) {
            echo "Student not found.\n";
            $this->logger->log(LogType::WARN, 'Update', "Student ID $id not found.");
            return;
        }

        echo "New first name (leave blank to keep '{$student->firstname}'): ";
        $firstname = trim(readline());
        echo "New last name (leave blank to keep '{$student->lastname}'): ";
        $lastname = trim(readline());
        echo "New DOB (yyyy-mm-dd, leave blank to keep '{$student->date_of_birth}'): ";
        $dob = trim(readline());
        echo "New email (leave blank to keep '{$student->email}'): ";
        $email = trim(readline());

        $student->firstname = $firstname ?: $student->firstname;
        $student->lastname = $lastname ?: $student->lastname;
        $student->date_of_birth = $dob ?: $student->date_of_birth;
        $student->email = $email ?: $student->email;

        try {
            $this->studentRepository->update($student);
            $this->logger->log(LogType::DEBUG, 'Update', "Updated student ID: $id");
            echo "Student updated.\n";
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Update', 'Exception during update: ' . $e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteStudent(): void
    {
        echo "ID to delete: ";
        $id = trim(readline());

        try {
            if ($this->studentRepository->deleteById($id)) {
                $this->logger->log(LogType::DEBUG, 'Deletion', "Deleted student ID: $id");
                echo "Student deleted.\n";
            } else {
                $this->logger->log(LogType::WARN, 'Deletion', "Student ID $id not found.");
                echo "Student not found.\n";
            }
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Deletion', 'Exception: ' . $e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public function searchStudentsByIdentity(): void
    {
        echo "Search by name: ";
        $input = trim(readline());

        try {
            $students = $this->studentRepository->findAllByName($input);
            echo "=== Search Results ===\n";
            foreach ($students as $student) {
                echo $student . PHP_EOL;
            }
            $this->logger->log(LogType::DEBUG, 'Search', "Searched students with input: $input");
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Search', 'Error during search: ' . $e->getMessage());
            echo "Error: " . $e->getMessage();
        }
    }

    public function getAllStudentsForView(): array
    {
        try {
            return $this->studentRepository->findAll();
        } catch (Exception $e) {
            $this->logger->log(LogType::ERR, 'Display', 'Error loading students for view: ' . $e->getMessage());
            return [];
        }
    }
}
