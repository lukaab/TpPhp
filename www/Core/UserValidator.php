<?php

namespace App\Core;

class UserValidator
{
    private $errors = []; // Tableau pour stocker les erreurs

    public function validate(array $data): bool
    {
        $this->validateFirstname($data['firstname'] ?? '');
        $this->validateLastname($data['lastname'] ?? '');
        $this->validateEmail($data['email'] ?? '');
        $this->validateCountry($data['country'] ?? '');
        $this->validatePassword($data['pwd'] ?? '', $data['pwdconf'] ?? '');

        return empty($this->errors); // Retourne true si aucune erreur
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateFirstname(string $firstname): void
    {
        if (empty($firstname)) {
            $this->errors[] = "Le prénom est obligatoire.";
        }
    }

    private function validateLastname(string $lastname): void
    {
        if (empty($lastname)) {
            $this->errors[] = "Le nom est obligatoire.";
        }
    }

    private function validateEmail(string $email): void
{
    if (empty($email)) {
        $this->errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->errors[] = "L'email n'est pas valide.";
    } else {
        // Vérifier l'unicité de l'email
        $sql = new SQL();
        $pdo = $sql->getPDO(); // Récupérer l'objet PDO
        $query = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
        $query->execute([$email]);
        $existingUser = $query->fetchColumn(); // Récupérer un résultat

        if ($existingUser) {
            $this->errors[] = "Cet email est déjà utilisé.";
        }
    }
}

    private function validateCountry(string $country): void
    {
        if (empty($country)) {
            $this->errors[] = "La nationalité est obligatoire.";
        } elseif (!preg_match('/^[a-zA-Z]{2}$/', $country)) {
            $this->errors[] = "La nationalité doit contenir exactement 2 lettres.";
        }
    }

    private function validatePassword(string $pwd, string $pwdconf): void
    {
        if (empty($pwd)) {
            $this->errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($pwd) < 8) {
            $this->errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (!preg_match('/[A-Z]/', $pwd)) {
            $this->errors[] = "Le mot de passe doit contenir au moins une lettre majuscule.";
        }

        if ($pwd !== $pwdconf) {
            $this->errors[] = "Les mots de passe ne correspondent pas.";
        }
    }


    
    public function validateLogin(array $data): bool
    {
        $this->validateLoginEmail($data['email'] ?? '');
        $this->validateLoginPassword($data['email'] ?? '', $data['pwd'] ?? '');

        return empty($this->errors); // Retourne true si aucune erreur
    }

    private function validateLoginEmail(string $email): void
    {
        if (empty($email)) {
            $this->errors[] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'email n'est pas valide.";
        }
    }

    private function validateLoginPassword(string $email, string $pwd): void
    {
        if (empty($pwd)) {
            $this->errors[] = "Le mot de passe est obligatoire.";
            return;
        }

        // Vérifier si l'email existe en base
        $sql = new SQL();
        $pdo = $sql->getPDO();
        $query = $pdo->prepare("SELECT password, firstname FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

        if (!$user) {
            $this->errors[] = "Identifiants incorrects.";
            return;
        }

        // Vérification du mot de passe
        if (!password_verify($pwd, $user['password'])) {
            $this->errors[] = "Identifiants incorrects.";
        } else {
            // Stocker le prénom pour affichage après connexion
            $_SESSION['firstname'] = $user['firstname'];
        }
    }

}