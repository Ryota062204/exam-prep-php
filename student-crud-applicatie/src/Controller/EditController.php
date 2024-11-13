<?php

namespace Src\Controller;

use Src\Model\Student;
use PDO;

class EditController
{
    private $twig;
    private $pdo;

    public function __construct($twig, PDO $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function render()
    {
        $id = $_GET['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $score = $_POST['score'];

            $student = Student::select($this->pdo, (int)$id);
            if ($student) {
                $student->setName($name);
                $student->setEmail($email);
                $student->setScore($score);
                $student->save($this->pdo);

                header('Location: ?page=overview');
                exit;
            }
        } else {
            $student = Student::select($this->pdo, (int)$id);
            if ($student) {
                echo $this->twig->render('edit.html.twig', ['student' => $student]);
            } else {
                header('Location: ?page=overview');
            }
        }
    }
}
