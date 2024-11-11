<?php

namespace Src\Controller;

use Src\Model\Teacher;
use Src\Model\User;
use Twig\Environment;
use PDO;

class RegisterController
{
    private $twig;
    private $pdo;

    public function __construct(Environment $twig, PDO $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function render()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;

            if ($username && $email && $password && $confirmPassword) {
                if ($password === $confirmPassword) {
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    $stmt = $this->pdo->prepare("INSERT INTO teachers (username, email, password) VALUES (:username, :email, :password)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->execute();

                    header("Location: ?page=login");
                    exit;
                } else {
                    echo "Passwords do not match!";
                }
            } 
        }

        echo $this->twig->render('register.html.twig');
    }
}
