<?php

namespace App\Core;

class UserValidator
{
    private $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    
    public function validateLogin(array $data): bool
    {
        $this->validateLoginEmail($data['email'] ?? '');
        $this->validateLoginPassword($data['email'] ?? '', $data['pwd'] ?? '');

        return empty($this->errors);
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
        $sql = new SQL();
        $pdo = $sql->getPDO();
        $query = $pdo->prepare("SELECT password, firstname FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch();

        if (!$user) {
            $this->errors[] = "Identifiants incorrects.";
            return;
        }

        if (!password_verify($pwd, $user['password'])) {
            $this->errors[] = "Identifiants incorrects.";
        } else {
            $_SESSION['firstname'] = $user['firstname'];
        }
    }

}