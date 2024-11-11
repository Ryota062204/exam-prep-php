<?php

namespace Src\Model;

abstract class User
{
    protected ?int $id;
    protected string $email;
    protected string $password;

    public function __construct(string $email, string $password, ?int $id = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    // Getters and Setters for the properties

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    // Login method for teacher (could be used for authentication)
    public static function login(string $email, string $password): ?User
    {
        global $pdo;
        $query = $pdo->prepare('SELECT * FROM teachers WHERE email = :email');
        $query->execute([':email' => $email]);
        $result = $query->fetch();

        if ($result && password_verify($password, $result['password'])) {
            return new Teacher($result['username'], $result['email'], $result['password'], (int) $result['id']);
        }

        return null;
    }
}
