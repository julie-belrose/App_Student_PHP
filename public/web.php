<?php

$services = require __DIR__ . '/../bootstrap.php';

$studentService = $services['studentService'];
$logger = $services['logger'];

handlePostRequest($studentService, $logger);

$students = $studentService->getAllStudentsForView();

renderView($students);


// ===== Functions =====

function handlePostRequest($studentService, $logger): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $action = $_POST['action'] ?? '';

    match ($action) {
        'create' => handleCreateStudent($studentService, $logger),
        'delete' => handleDeleteStudent($studentService, $logger),
        default => null,
    };
}

function handleCreateStudent($studentService, $logger): void
{
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($firstname) || empty($lastname)) {
        $logger->log(\src\model\LogType::WARN, 'Insertion', 'Missing firstname or lastname (web)');
        return;
    }

    $student = new \src\model\Student(null, $firstname, $lastname, $dob ?: null, $email ?: null);
    $studentService->getRepository()->save($student);

    $logger->log(\src\model\LogType::DEBUG, 'Insertion', "Added student $firstname $lastname via web");
}

function handleDeleteStudent($studentService, $logger): void
{
    $id = trim($_POST['id'] ?? '');

    if (empty($id)) {
        $logger->log(\src\model\LogType::WARN, 'Deletion', 'Empty ID on web deletion');
        return;
    }

    $studentService->getRepository()->deleteById($id);
    $logger->log(\src\model\LogType::DEBUG, 'Deletion', "Deleted student ID $id via web");
}

function renderView(array $students): void
{
    ob_start(); // starts output buffering
    include __DIR__ . '/templates/studentView.php';
    echo ob_get_clean(); //Nothing is displayed until you say so
}
