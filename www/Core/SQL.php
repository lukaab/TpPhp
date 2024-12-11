<?php

namespace App\Core;

class SQL
{
    private $pdo;

    public function __construct(){
        try {
            $this->pdo = new \PDO("mysql:host=mariadb;dbname=esgi", "esgi", "esgipwd");
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    public function getOneById(string $table, int $id): array
    {
        $queryPrepared = $this->pdo->prepare("SELECT * FROM " . $table . " WHERE id=:id");
        $queryPrepared->execute([
            "id" => $id
        ]);
        return $queryPrepared->fetch();
    }

    public function getUserByEmail(string $email): array
{
    $queryPrepared = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
    $queryPrepared->execute([':email' => $email]);
    $results = $queryPrepared->fetch();
    if ($results) {
        print_r($results);
    } else {
        echo "Aucun utilisateur trouvé pour cet email.";
    }
    
    return $results ?? [];
}

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }
}