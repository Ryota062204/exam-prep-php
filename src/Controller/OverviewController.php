<?php

namespace Src\Controller;

use Src\Model\Student;
use Twig\Environment;

class OverviewController
{
    private Environment $twig;
    private \PDO $pdo;

    public function __construct(Environment $twig, \PDO $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function render()
    {
        $minScore = isset($_GET['score']) && $_GET['score'] !== '' ? (int)$_GET['score'] : null;
        $nameFilter = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null;
        
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $studentsPerPage = 12;
        $offset = ($currentPage - 1) * $studentsPerPage;

        $totalStudents = Student::countFiltered($this->pdo, $minScore, $nameFilter);
        $totalPages = ceil($totalStudents / $studentsPerPage);

        $students = Student::selectAll($this->pdo, $minScore, $nameFilter, $studentsPerPage, $offset);

        echo $this->twig->render('overview.html.twig', [
            'students' => $students,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }
}
