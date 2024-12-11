<?php
namespace App\Controllers;

use App\Core\User as U;
use App\Core\View;
use App\Core\SQL;
use App\Core\UserValidator;

class User
{

    public function register(): void
{
    $errors = []; // Tableau pour stocker les erreurs

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données soumises
        $data = [
            'firstname' => trim($_POST['firstname'] ?? ''),
            'lastname' => trim($_POST['lastname'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'country' => trim($_POST['country'] ?? ''),
            'pwd' => $_POST['pwd'] ?? '',
            'pwdconf' => $_POST['pwdconf'] ?? ''
        ];

        // Validation
        $validator = new UserValidator();
        if ($validator->validate($data)) {
            // Insérer l'utilisateur en base
            $hashedPwd = password_hash($data['pwd'], PASSWORD_DEFAULT);

            $sql = new SQL(); // Instance de SQL
            $pdo = $sql->getPDO(); // Récupérer l'objet PDO

            $query = $pdo->prepare(
                "INSERT INTO users (firstname, lastname, email, country, password) VALUES (?, ?, ?, ?, ?)"
            );
            $query->execute([
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['country'],
                $hashedPwd
            ]);

            // Rediriger vers la page de connexion
            header("Location: /se-connecter");
            exit();
        } else {
            // Collecter les erreurs
            $errors = $validator->getErrors();
        }
    }

    // Passer les erreurs à la vue
    $view = new View("User/register.php", "back.php");
    $view->addData("errors", $errors);
}

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


public function logout(): void
{
    session_start(); // Démarrer la session pour y accéder
    session_unset(); // Supprimer toutes les variables de session
    session_destroy(); // Détruire la session

    // Rediriger l'utilisateur vers la page de connexion
    header("Location: /se-connecter");
    exit();
}

}