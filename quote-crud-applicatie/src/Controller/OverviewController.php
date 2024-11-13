<?php

namespace Src\Controller;

use Src\Model\Quotes;
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
        $authorFilter = isset($_GET['author']) && $_GET['author'] !== '' ? $_GET['author'] : null;

        $quotes = Quotes::selectAll($this->pdo, $authorFilter);

        echo $this->twig->render('overview.html.twig', ['quotes' => $quotes, 'authorFilter' => $authorFilter]);
    }
}
