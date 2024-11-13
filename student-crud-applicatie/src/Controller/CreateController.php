<?php

namespace Src\Controller;

use Src\Model\Student;
use Src\Model\Teacher;
use Twig\Environment;
use PDO;

class CreateController
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
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $score = $_POST['score'] ?? null;

            if ($name && $email && $score) {
                $stmt = $this->pdo->prepare("INSERT INTO students (name, email, score) VALUES (:name, :email, :score)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':score', $score);
                $stmt->execute();
            }
        }

        echo $this->twig->render('create.html.twig');
    }
}
