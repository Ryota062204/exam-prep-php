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
        
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Perform login logic here
            $teacher = Teacher::login($this->pdo, $email, $password);

            if ($teacher) {
                // Redirect to the overview page after successful login
                header("Location: ?page=overview");
                exit;
            } else {
                // Show error message if login fails
                $error = "Invalid email or password.";
            }
        }

        // Render the login page using Twig
        echo $this->twig->render('login.html.twig', ['error' => $error ?? '']);
    }
}
