<?php
namespace App\Controllers;

use App\Core\User as U;
use App\Core\View;
use App\Core\SQL;
use App\Core\UserValidator;

class User
{
    public function login(): void
{
    $errors = []; // Tableau des erreurs

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = new SQL();
        $user = new User($sql->getPDO());

        // Récupération des données du formulaire
        $data = [
            'email' => trim($_POST['email'] ?? ''),
            'pwd' => $_POST['pwd'] ?? ''
        ];

        // Validation
        $validator = new UserValidator();
        if ($validator->validateLogin($data)) {
            // Récupérer les informations utilisateur
            session_start();
            $userData = $sql->getUserByEmail($data['email']);
            if (!empty($userData)) {
                $_SESSION['firstname'] = $userData['firstname']; // Stocker uniquement le prénom
                header("Location: /home");
                exit();
            } else {
                $errors[] = "Erreur lors de la récupération des données utilisateur.";
            }
        } else {
            // Collecter les erreurs
            $errors = $validator->getErrors();
        }
    }

    $view = new View("User/login.php", "back.php");
    $view->addData("errors", $errors);
}
}