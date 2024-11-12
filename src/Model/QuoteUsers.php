<?php

namespace Src\Model;

use PDO;

class QuoteUsers implements CrudInterface
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function save(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }

    public static function select(PDO $pdo, int $id): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return null;
    }

    public function delete(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public static function selectAll(PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM users");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return $results;
    }

    public static function login(PDO $pdo, string $email, string $password): ?self
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            return new self($row['username'], $row['email'], $row['password'], $row['id']);
        }
        return null;
    }
}
