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
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirmPassword = $_POST['confirm_password'] ?? null;

            // Validate input fields
            if ($username && $email && $password && $confirmPassword) {
                // Check if passwords match
                if ($password === $confirmPassword) {
                    // Hash the password before saving
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                    // Insert user into the database
                    $stmt = $this->pdo->prepare("INSERT INTO teachers (username, email, password) VALUES (:username, :email, :password)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->execute();

                    // Redirect to login page after successful registration
                    header("Location: ?page=login");
                    exit;
                } else {
                    // If passwords do not match
                    echo "Passwords do not match!";
                }
            } else {
                // Handle missing fields
                echo "Please fill in all the fields!";
            }
        }

        // Render the register form
        echo $this->twig->render('register.html.twig');
    }
}
