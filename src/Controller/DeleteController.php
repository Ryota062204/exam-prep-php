<?php

namespace Src\Controller;

use Src\Model\Student;

class DeleteController
{
    public function render()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $student = Student::select($pdo, $id);
            $student->delete($pdo);
        }
        
        header('Location: ?page=overview');
        exit;
    }
}
