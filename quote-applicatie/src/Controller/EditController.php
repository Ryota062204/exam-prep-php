<?php

namespace Src\Controller;

use Src\Model\Quotes;
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
            $quote = $_POST['quote'];
            $author = $_POST['author'];
            $genre = $_POST['genre'];
            $description = $_POST['description'];

            $quoteObj = Quotes::select($this->pdo, (int)$id);
            if ($quoteObj) {
                $quoteObj->setQuote($quote);
                $quoteObj->setAuthor($author);
                $quoteObj->setGenre($genre);
                $quoteObj->setQuoteDescription($description);
                $quoteObj->save($this->pdo);

                header('Location: ?page=overview');
                exit;
            }
        } else {
            $quoteObj = Quotes::select($this->pdo, (int)$id);
            if ($quoteObj) {
                echo $this->twig->render('edit.html.twig', ['quote' => $quoteObj]);
            } else {
                header('Location: ?page=overview');
            }
        }
    }
}
