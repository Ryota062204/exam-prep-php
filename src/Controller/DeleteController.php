<?php

namespace Src\Controller;

use Src\Model\Student;
use PDO;

class DeleteController
{
    private $pdo;

    public function __construct(PDO $pdo) 
    {
        $this->pdo = $pdo;
    }

    public function render()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $student = Student::select($this->pdo, (int)$id);
            if ($student) {
                $student->delete($this->pdo);
            }
        }

        header('Location: ?page=overview');
        exit;
    }
}
