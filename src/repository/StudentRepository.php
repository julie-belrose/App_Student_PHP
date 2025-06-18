<?php

namespace src\repository;

use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use src\model\Student;

class StudentRepository {
    public function __construct(private Collection $collection) {}

    private function documentToStudent(object $doc): Student
    {
        return new Student(
            (string)$doc->_id,
            $doc->firstname ?? null,
            $doc->lastname ?? null,
            $doc->date_of_birth ?? null,
            $doc->email ?? null
        );
    }

    public function findAll() {
        $cursor = $this->collection->find([], ['sort' => ['lastname' => 1, 'firstname' => 1]]);
        $students = [];

        foreach ($cursor as $doc) {
            $students[] = $this->documentToStudent($doc);
        }

        return $students;
    }

    // Récupère un étudiant par son id
    public function findById(string $id): Student|false
    {
        try {
            $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
            return $doc ? $this->documentToStudent($doc) : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function save(Student $student) {
        $request = "INSERT INTO student (firstname, lastname, date_of_birth, email)
                    VALUES (:firstname, :lastname, :date_of_birth, :email)";

        $statement = $this->db->prepare($request);

        // Le charactère ":" est facultatif lorsque l'on bind les paramètres
        $statement->bindValue(":firstname", $student->firstname);
        $statement->bindValue(":lastname", $student->lastname);
        $statement->bindValue(":date_of_birth", $student->date_of_birth);
        $statement->bindValue(":email", $student->email);

        $statement->execute();

        // Retourne le nombre de lignes affectés par la requête
        return $statement->rowCount() === 1;
    }

    public function update(Student $studentToUpdate) {
        $request = "UPDATE student SET firstname=:firstname, lastname=:lastname, date_of_birth=:date_of_birth, email=:email WHERE id=:id;";
        $statement = $this->db->prepare($request);

        $statement->bindValue(":firstname", $studentToUpdate->firstname);
        $statement->bindValue(":lastname", $studentToUpdate->lastname);
        $statement->bindValue(":date_of_birth", $studentToUpdate->date_of_birth);
        $statement->bindValue(":email", $studentToUpdate->email);
        $statement->bindValue(":id", $studentToUpdate->id);

        return $statement->execute();
    }

    public function deleteById($id) {
        $query = "DELETE FROM student WHERE id=:id;";
        $statement = $this->db->prepare($query);
        return $statement->execute(["id" => $id]);
    }

    public function findAllByName($input) {
        $request = "SELECT id, firstname, lastname, date_of_birth, email FROM student WHERE firstname LIKE :input OR lastname LIKE :input";
        $statement = $this->db->prepare($request);
        $statement->execute([":input" => $input]);
        $students = $statement->fetchAll(PDO::FETCH_ASSOC);
        return StudentMapper::entitiesToStudents($students);
    }
}