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

    public function getId(): int { 
        return $this->id;
     }

    public function setId(int $id): void { 
        $this->id = $id; 
    }

    public function getName(): string
     { return $this->name; 
    }
    public function setName(string $name): void 
    { $this->name = $name;
     }

    public function getScore(): int { return 
        $this->score; 
    }
    public function setScore(int $score): void { 
        $this->score = $score;
     }

    public function getEmail(): string { return
         $this->email; 
    }
    public function setEmail(string $email): void { 
        $this->email = $email; 
    }


    public function save(PDO $pdo): bool
    {
        if ($this->id === 0) {
            $stmt = $pdo->prepare("INSERT INTO students (name, score, email) VALUES (:name, :score, :email)");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':score', $this->score);
            $stmt->bindParam(':email', $this->email);
            return $stmt->execute();
        } else {
            $stmt = $pdo->prepare("UPDATE students SET name = :name, score = :score, email = :email WHERE id = :id");
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':score', $this->score);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':id', $this->id);
            return $stmt->execute();
        }
    }

    public function delete(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

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

    public static function selectAll(PDO $pdo, ?int $minScore = null, ?string $nameFilter = null): array
    {
        $query = "SELECT * FROM students WHERE 1";
        $params = [];

        if ($minScore !== null) {
            $query .= " AND score >= :minScore";
            $params['minScore'] = $minScore;
        }

        if ($nameFilter !== null) {
            $query .= " AND name LIKE :name";
            $params['name'] = $nameFilter . '%';
        }

        $query .= " ORDER BY name ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
