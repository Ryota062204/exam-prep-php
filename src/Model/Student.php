<?php

namespace Src\Model;

use PDO;

class Student implements CrudInterface
{
    private int $id;
    private string $name;
    private int $score;
    private string $email;

    // Constructor
    public function __construct(int $id, string $name, int $score, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->score = $score;
        $this->email = $email;
    }

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Implementing the save method from CrudInterface
    public function save(PDO $pdo): bool
    {
        if ($this->id === 0) {
            // Insert new student
            $stmt = $pdo->prepare("INSERT INTO students (name, score, email) VALUES (:name, :score, :email)");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':score', $this->score);
            $stmt->bindParam(':email', $this->email);
            return $stmt->execute();
        } else {
            // Update existing student
            $stmt = $pdo->prepare("UPDATE students SET name = :name, score = :score, email = :email WHERE id = :id");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':score', $this->score);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        }
    }

    // Implementing the delete method from CrudInterface
    public function delete(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Implementing the select method from CrudInterface
    public static function select(PDO $pdo, int $id): ?Student
    {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($studentData) {
            return new self(
                $studentData['id'],
                $studentData['name'],
                $studentData['score'],
                $studentData['email']
            );
        }

        return null;
    }

    // Implementing the selectAll method from CrudInterface
    public static function selectAll(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM students");
        $stmt->execute();

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convert each row to a Student object
        $studentObjects = [];
        foreach ($students as $studentData) {
            $studentObjects[] = new self(
                $studentData['id'],
                $studentData['name'],
                $studentData['score'],
                $studentData['email']
            );
        }

        return $studentObjects;
    }
}
