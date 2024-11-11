<?php

namespace Src\Model;

class Teacher extends User implements CrudInterface
{
    private string $username;

    public function __construct(string $username, string $email, string $password, ?int $id = null)
    {
        parent::__construct($email, $password, $id);
        $this->username = $username;
    }

    // Getters and Setters for the properties

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function save(\PDO $pdo)
    {
        if ($this->getId()) {
            $query = $pdo->prepare('UPDATE teachers SET username = :username, email = :email WHERE id = :id');
            $query->execute([
                ':id' => $this->getId(),
                ':username' => $this->getUsername(),
                ':email' => $this->getEmail()
            ]);
        } else {
            $query = $pdo->prepare('INSERT INTO teachers (username, email, password) VALUES (:username, :email, :password)');
            $query->execute([
                ':username' => $this->getUsername(),
                ':email' => $this->getEmail(),
                ':password' => password_hash($this->getPassword(), PASSWORD_DEFAULT)
            ]);
            $this->id = (int) $pdo->lastInsertId();
        }
    }

    public static function select(\PDO $pdo, int $id)
    {
        $query = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
        $query->execute([':id' => $id]);
        $result = $query->fetch();
        return new Teacher($result['username'], $result['email'], $result['password'], (int) $result['id']);
    }

    public function delete(\PDO $pdo)
    {
        $query = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $query->execute([':id' => $this->getId()]);
    }
}
