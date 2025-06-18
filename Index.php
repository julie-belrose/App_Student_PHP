<?php

require './vendor/autoload.php';

use MongoDB\Client;
use src\repository\StudentRepository;
use src\service\StudentService;

// Affichage du menu
function menu(): void
{
    echo "
       _             _ _             _
   ___| |_ _   _  __| (_) __ _ _ __ | |_ ___
  / _ \ __| | | |/ _` | |/ _` | '_ \| __/ __|
 |  __/ |_| |_| | (_| | | (_| | | | | |_\__ \
  \___|\__|\__,_|\__,_|_|\__,_|_| |_|\__|___/" . PHP_EOL;

    echo "1. Afficher les étudiants
2. Créer un étudiant
3. Editer un étudiant
4. Supprimer un étudiant
5. Chercher par nom ou prénom
6. Quitter" . PHP_EOL;
}

try {
    $client = new Client("mongodb://localhost:27017");
    $db = $client->student_app;
    $collection = $db->students;

    echo "Connexion réussie à MongoDB !", PHP_EOL;

    $studentRepo = new StudentRepository($collection);
    $studentService = new StudentService($studentRepo);

    while (true) {
        menu();
        $input = readline("Saisir une option: ");
        match ($input) {
            "1" => $studentService->displayStudents(),
            "2" => $studentService->createStudent(),
            "3" => $studentService->editStudent(),
            "4" => $studentService->deleteStudent(),
            "5" => $studentService->searchStudentsByIdentity(),
            "6" => exit(),
            default => print("saisie invalide"),
        };

        echo "\n---Appuyez sur Entrée pour continuer---\n";
        readline();
    }

} catch (Exception $e) {
    echo "Erreur MongoDB : " . $e->getMessage(), PHP_EOL;
}
