<?php

namespace Src\Controller;

use Src\Model\Quotes;
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
            $quote = $_POST['quote'] ?? null;
            $author = $_POST['author'] ?? null;
            $genre = $_POST['genre'] ?? null;
            $description = $_POST['description'] ?? null;
            $date = $_POST['date'] ?? null;

            if ($quote && $author && $genre && $description && $date) {
                $stmt = $this->pdo->prepare("INSERT INTO quotes (quote, author, genre, description, date) VALUES (:quote, :author, :genre, :description, :date)");
                $stmt->bindParam(':quote', $quote);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':genre', $genre);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
            }
        }

        echo $this->twig->render('create.html.twig');
    }
}
