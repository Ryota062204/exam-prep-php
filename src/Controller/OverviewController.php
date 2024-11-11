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
        // Fetch all students from the database
        $students = Student::selectAll($this->pdo);

        // Render the overview page using Twig
        echo $this->twig->render('overview.html.twig', ['students' => $students]);
    }
}
