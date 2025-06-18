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

    public function save(Student $student): bool
    {
        $count = $this->collection->countDocuments();
        if ($count >= 50) {
            throw new \RuntimeException("Limit of 50 students reached.");
        }

        if (empty($student->firstname) || empty($student->lastname)) {
            throw new \InvalidArgumentException("Firstname and lastname are required.");
        }

        $doc = [
            'firstname' => $student->firstname,
            'lastname' => $student->lastname,
        ];

        if (!empty($student->date_of_birth)) {
            $doc['date_of_birth'] = $student->date_of_birth;
        }

        if (!empty($student->email)) {
            $doc['email'] = $student->email;
        }

        $result = $this->collection->insertOne($doc);
        return $result->isAcknowledged();
    }

    public function update(Student $student): bool
    {
        if (!$student->id) {
            throw new \InvalidArgumentException("ID is required to update.");
        }

        $doc = [
            'firstname' => $student->firstname,
            'lastname' => $student->lastname,
        ];

        if (!empty($student->date_of_birth)) {
            $doc['date_of_birth'] = $student->date_of_birth;
        }

        if (!empty($student->email)) {
            $doc['email'] = $student->email;
        }

        $result = $this->collection->updateOne(
            ['_id' => new ObjectId($student->id)],
            ['$set' => $doc]
        );

        return $result->getModifiedCount() > 0;
    }

    public function deleteById(string $id): bool
    {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount() > 0;
    }

    public function findAllByName(string $input): array
    {
        $regex = new \MongoDB\BSON\Regex($input, 'i');
        $cursor = $this->collection->find([
            '$or' => [
                ['firstname' => $regex],
                ['lastname' => $regex],
            ]
        ]);

        $students = [];
        foreach ($cursor as $doc) {
            $students[] = $this->documentToStudent($doc);
        }

        return $students;
    }
}