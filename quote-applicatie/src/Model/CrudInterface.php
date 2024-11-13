<?php

namespace Src\Model;

interface CrudInterface
{
    public function save(\PDO $pdo);
    public static function select(\PDO $pdo, int $id);
    public function delete(\PDO $pdo);
    public static function selectAll(\PDO $pdo): array;  
}
