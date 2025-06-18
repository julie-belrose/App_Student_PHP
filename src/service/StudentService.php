<?php

namespace src\service;

use src\model\Student;
use src\repository\StudentRepository;

class StudentService
{
    // Définition des regex à utiliser sous forme de constantes
    const DATE_PATTERN = "/^\d{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])$/";
    const EMAIL_PATTERN = "/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,}$/";

    public function __construct(private StudentRepository $studentRepository){}

    // Permet d'afficher les étudiants
    function displayStudents(): void
    {
        $students = [];
        try{
            $students = $this->studentRepository->findAll();
        } catch (PDOException $e) {
            print("Erreur lors de findAll : " . $e->getMessage());
        }

        echo "=== Affichage des étudiants ===\n";
        if(empty($students))
            echo "Aucun étudiant";

        foreach ($students as $student) {
            // On affiche chaque étudiant récupéré depuis la base de données
            echo $student . PHP_EOL;
        }
    }

    // Créé un étudiant et effectue des vérifications
    function createStudent(): bool
    {
        echo "Saisir le prénom : ";
        $firstname = readline();

        if (empty($firstname)) {
            echo "Prénom incorrect";
            return false;
        }

        echo "Saisir le nom : ";
        $lastname = readline();

        if (empty($lastname)) {
            echo "Nom incorrect";
            return false;
        }

        echo "Saisir date naissance (aaaa-mm-jj): ";
        $dob = readline();

        if (!preg_match(self::DATE_PATTERN, $dob)) {
            echo "Date incorrecte";
            return false;
        }

        echo "Saisir email: ";
        $email = readline();

        if (!preg_match(self::EMAIL_PATTERN, $email)) {
            echo "Email incorrect";
            return false;
        }

        try {
            $this->studentRepository->save(new Student(null, $firstname, $lastname, $dob, $email));
            return true;
        } catch (PDOException $e) {
            print("Erreur lors de save : " . $e->getMessage());
            return false;
        }
    }

    // Permet d'éditer un étudiant
    function editStudent(): void
    {
        echo "Saisir l'id de l'étudiant: ";
        $id = (int)readline();

        try{
            // On récupère l'étudiant en base de données s'il existe
            $student = $this->studentRepository->findById($id);
        } catch (PDOException $e) {
            print("Erreur lors de findById : " . $e->getMessage());
            $student = false;
        }

        // Si l'étudiant n'est pas trouvé, on quitte la fonction
        if (!$student) {
            echo "Aucun étudiant trouvé avec l'id {$id}";
            return;
        }
        readline();

        echo "Saisir prénom: ";
        $firstname = readline();

        // Si l'utilisateur ne saisit rien, firstname garde son ancienne valeur
        if (!empty($firstname)) {
            $student->firstname = $firstname;
        }

        echo "Saisir nom: ";
        $lastname = readline();

        if (!empty($lastname)) {
            $student->lastname = $lastname;
        }

        echo "Saisir date naissance: ";
        $dob = readline();

        if (!empty($dob) && preg_match(self::DATE_PATTERN, $dob)) {
            $student->date_of_birth = $dob;
        }

        echo "Saisir email: ";
        $email = readline();

        if (!empty($email) && preg_match(self::EMAIL_PATTERN, $email)) {
            $student->email = $email;
        }

        try {
            $this->studentRepository->update($student);
        } catch (PDOException $e) {
            print("Erreur lors de update : " . $e->getMessage());
        }
    }

    // Supprime un étudiant par son id
    function deleteStudent(): void
    {
        echo "Saisir l'id de l'étudiant: ";
        $id = (int)readline();

        try{
            $success = $this->studentRepository->deleteById($id);
        } catch (PDOException $e) {
            print("Erreur lors de deleteById : " . $e->getMessage());
            $success = false;
        }

        if($success)
            echo "L'étudiant avec l'ID $id a été supprimé.\n";
        else
            echo "L'id est incorrecte.\n";
    }

    function searchStudentsByIdentity(): void {
        // On prépare le paramètre pour le like
        echo "Saisir le nom ou prénom de l'étudiant: ";
        $input = '%' . readline() . '%';

        $students = [];
        try{
            $students = $this->studentRepository->findAllByName($input);
        } catch (PDOException $e) {
            print("Erreur lors de findAllByName : " . $e->getMessage());
        }

        echo "=== Affichage de tout étudiants ayant $input dans leur nom ou prénom === \n";
        foreach ($students as $student) {
            // On affiche chaque étudiant récupéré depuis la base de données
            echo $student . PHP_EOL;
        }
    }
}