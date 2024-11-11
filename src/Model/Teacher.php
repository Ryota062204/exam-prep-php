<?php

namespace Src\Model;

use PDO;

class Teacher implements CrudInterface
{
    private int $id;
    private string $username;
    private string $email;
    private string $password;

    public function __construct(string $username, string $email, string $password, int $id = 0)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    // Getter and Setter for ID
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter and Setter for Username
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    // Getter and Setter for Email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Getter and Setter for Password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Implementing the save method
    public function save(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("INSERT INTO teachers (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }

    // Implementing the select method
    public static function select(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return null;
    }

    // Implementing the delete method
    public function delete(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Implementing the selectAll method
    public static function selectAll(PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM teachers");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return $results;
    }

    // Login method for authentication
    public static function login(PDO $pdo, string $email, string $password): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            return new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return null;
    }
}
