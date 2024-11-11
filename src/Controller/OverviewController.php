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

        $students = Student::selectAll($this->pdo, $minScore, $nameFilter);

        echo $this->twig->render('overview.html.twig', ['students' => $students]);
    }
}
