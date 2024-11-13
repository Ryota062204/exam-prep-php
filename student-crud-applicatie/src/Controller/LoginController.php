<?php

namespace Src\Controller;

use Src\Model\Teacher;

class LoginController
{
    private $twig;
    private $pdo;

    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function render()
    {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $teacher = Teacher::login($this->pdo, $email, $password);

            if ($teacher) {
                header("Location: ?page=overview");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }

        echo $this->twig->render('login.html.twig', ['error' => $error ?? '']);
    }
}