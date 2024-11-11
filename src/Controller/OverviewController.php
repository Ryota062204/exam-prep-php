<?php

namespace Src\Controller;

use Src\Model\Student;

class OverviewController
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
        $students = Student::selectAll($this->pdo);

        echo $this->twig->render('overview.html.twig', ['students' => $students]);
    }
}
