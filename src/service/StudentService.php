<?php

namespace src\service;

use src\model\Student;
use src\repository\StudentRepository;
use src\model\Logger;
use src\model\LogType;
use PDOException;

class StudentService
{
    const DATE_PATTERN = "/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/";
    const EMAIL_PATTERN = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/";

    public function __construct(
        private StudentRepository $studentRepository,
        private Logger $logger
    ) {}

    public function displayStudents(): void
    {
        try {
            $students = $this->studentRepository->findAll();
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Display', 'Error during findAll: ' . $e->getMessage());
            print("Erreur lors de findAll : " . $e->getMessage());
            return;
        }

        echo "=== List of students ===\n";
        if (empty($students)) {
            echo "No students found.";
        }

        foreach ($students as $student) {
            echo $student . PHP_EOL;
        }

        $this->logger->log(LogType::DEBUG, 'Display', 'Displayed all students.');
    }

    public function createStudent(): bool
    {
        echo "Enter first name: ";
        $firstname = readline();
        if (empty($firstname)) {
            echo "Invalid first name";
            return false;
        }

        echo "Enter last name: ";
        $lastname = readline();
        if (empty($lastname)) {
            echo "Invalid last name";
            return false;
        }

        echo "Enter date of birth (yyyy-mm-dd): ";
        $dob = readline();
        if (!preg_match(self::DATE_PATTERN, $dob)) {
            echo "Invalid date";
            return false;
        }

        echo "Enter email: ";
        $email = readline();
        if (!preg_match(self::EMAIL_PATTERN, $email)) {
            echo "Invalid email";
            return false;
        }

        try {
            $this->studentRepository->save(new Student(null, $firstname, $lastname, $dob, $email));
            $this->logger->log(LogType::DEBUG, 'Insertion', "Student created: {$firstname} {$lastname}");
            return true;
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Insertion', "Error during student creation: " . $e->getMessage());
            print("Erreur lors de save : " . $e->getMessage());
            return false;
        }
    }

    public function editStudent(): void
    {
        echo "Enter student ID: ";
        $id = (int)readline();

        try {
            $student = $this->studentRepository->findById($id);
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Update', "Error during findById: " . $e->getMessage());
            print("Erreur lors de findById : " . $e->getMessage());
            return;
        }

        if (!$student) {
            echo "No student found with ID {$id}";
            return;
        }

        echo "Enter new first name (leave blank to keep current): ";
        $firstname = readline();
        if (!empty($firstname)) {
            $student->firstname = $firstname;
        }

        echo "Enter new last name: ";
        $lastname = readline();
        if (!empty($lastname)) {
            $student->lastname = $lastname;
        }

        echo "Enter new date of birth: ";
        $dob = readline();
        if (!empty($dob) && preg_match(self::DATE_PATTERN, $dob)) {
            $student->date_of_birth = $dob;
        }

        echo "Enter new email: ";
        $email = readline();
        if (!empty($email) && preg_match(self::EMAIL_PATTERN, $email)) {
            $student->email = $email;
        }

        try {
            $this->studentRepository->update($student);
            $this->logger->log(LogType::DEBUG, 'Update', "Student updated: ID {$student->id}");
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Update', "Error during update: " . $e->getMessage());
            print("Erreur lors de update : " . $e->getMessage());
        }
    }

    public function deleteStudent(): void
    {
        echo "Enter student ID: ";
        $id = (int)readline();

        try {
            $success = $this->studentRepository->deleteById($id);
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Suppression', "Error during deleteById: " . $e->getMessage());
            print("Erreur lors de deleteById : " . $e->getMessage());
            return;
        }

        if ($success) {
            echo "Student with ID $id deleted.\n";
            $this->logger->log(LogType::WARN, 'Suppression', "Student deleted: ID $id");
        } else {
            echo "Invalid ID.\n";
            $this->logger->log(LogType::ERR, 'Suppression', "Failed to delete student with ID: $id");
        }
    }

    public function searchStudentsByIdentity(): void
    {
        echo "Enter name or first name to search: ";
        $input = '%' . readline() . '%';

        try {
            $students = $this->studentRepository->findAllByName($input);
        } catch (PDOException $e) {
            $this->logger->log(LogType::ERR, 'Search', "Error during search: " . $e->getMessage());
            print("Erreur lors de findAllByName : " . $e->getMessage());
            return;
        }

        echo "=== Students matching {$input} === \n";
        foreach ($students as $student) {
            echo $student . PHP_EOL;
        }

        $this->logger->log(LogType::DEBUG, 'Search', "Performed search for identity containing: {$input}");
    }
}
