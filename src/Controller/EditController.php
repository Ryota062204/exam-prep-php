<?php

namespace Src\Controller;

use Src\Model\Student;
use Twig\Environment;
use PDO;

class EditController
{
    private Environment $twig;
    private PDO $pdo;

    public function __construct(Environment $twig, PDO $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function render()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student = Student::select($this->pdo, (int) $_GET['id']);
            $student->setName($_POST['name']);
            $student->setScore($_POST['score']);
            $student->setEmail($_POST['email']);
            $student->save($this->pdo);

            header('Location: index.php?page=overview');
            exit;
        }

        $student = Student::select($this->pdo, (int) $_GET['id']);
        echo $this->twig->render('edit.html.twig', ['student' => $student]);
    }
}
