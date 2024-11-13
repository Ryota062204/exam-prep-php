<?php

namespace Src\Model;

use PDO;

class Quotes implements CrudInterface
{
    private int $id;
    private string $quote;
    private string $author;
    private string $creationDate;
    private string $genre;
    private string $quoteDescription;

    public function __construct(int $id, string $quote, string $author, string $creationDate, string $genre, string $quoteDescription)
    {
        $this->id = $id;
        $this->quote = $quote;
        $this->author = $author;
        $this->creationDate = $creationDate;
        $this->genre = $genre;
        $this->quoteDescription = $quoteDescription;
    }

    public function getQuoteDescription(): string
    {
        return $this->quoteDescription;
    }

    public function setQuoteDescription(string $quoteDescription): void
    {
        $this->quoteDescription = $quoteDescription;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getQuote(): string
    {
        return $this->quote;
    }

    public function setQuote(string $quote): void
    {
        $this->quote = $quote;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function save(PDO $pdo): bool
    {
        if ($this->id === 0) {
            $stmt = $pdo->prepare("INSERT INTO quotes (quote, author, date, genre, description) VALUES (:quote, :author, :creationDate, :genre, :quoteDescription)");
        } else {
            $stmt = $pdo->prepare("UPDATE quotes SET quote = :quote, author = :author, date = :creationDate, genre = :genre, description = :quoteDescription WHERE id = :id");
            $stmt->bindParam(':id', $this->id);
        }

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':creationDate', $this->creationDate);
        $stmt->bindParam(':genre', $this->genre);
        $stmt->bindParam(':quoteDescription', $this->quoteDescription);

        return $stmt->execute();
    }

    public function delete(PDO $pdo): bool
    {
        $stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    public static function select(PDO $pdo, int $id): ?Student
    {
        $stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quoteData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quoteData) {
            return new self(
                $quoteData['id'],
                $quoteData['quote'],
                $quoteData['author'],
                $quoteData['date'],
                $quoteData['genre'],
                $quoteData['description']
            );
        }

        return null;
    }

    public static function selectAll(PDO $pdo, ?string $authorFilter = null): array
    {
        $query = "SELECT * FROM quotes WHERE 1";  
        $params = [];

        if ($authorFilter) {
            $query .= " AND author LIKE :author";
            $params[':author'] = "%$authorFilter%";  
        }

        $query .= " ORDER BY id ASC"; 
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $quoteObjects = [];
        foreach ($quotes as $quoteData) {
            $quoteObjects[] = new self(
                $quoteData['id'],
                $quoteData['quote'],
                $quoteData['author'],
                $quoteData['date'],
                $quoteData['genre'],
                $quoteData['description']
            );
        }

        return $quoteObjects;
    }
}
