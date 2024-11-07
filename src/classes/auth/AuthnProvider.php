<?php

namespace iutnc\deefy\auth;

use Exception;
use iutnc\deefy\exception as E;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\users\User;
use PDO;
use iutnc\deefy\repository\DeefyRepository;
use PDOException;

class AuthnProvider {


    private deefyRepository $deefyRepository;

    public function __construct() {
        $this->deefyRepository = DeefyRepository::getInstance();
    }

    public static function signin(string $email, string $passwd2check): void {
        $repo = DeefyRepository::getInstance();
        try {
            if ($repo->loginUser($email, $passwd2check)) {
                $_SESSION['user'] = serialize(new User($email));

            }
        } catch (AuthnException $e) {
            throw new E\AuthnException("Impossible de se connecter : " . $e->getMessage());
        }
    }

    public static function register(string $email, string $password): void
    {
        $repo = DeefyRepository::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new E\AuthnException("L'adresse email est invalide");
        }
        if ((new AuthnProvider)->checkPasswordStrength($password, 10)) {
            $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $query = $repo->registerUser($email, $hash, 1);

            if (!$query) {
                throw new E\AuthnException('Erreur lors de l\'enregistrement de l\'utilisateur.');
            }

            try {
                $_SESSION['user'] = serialize(new User($email));

            } catch (Exception $e) {
                throw new Exception('Erreur lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage());
            }

        } else {
            throw new E\AuthnException("Le mot de passe doit contenir au moins 8 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }
    }


    public function checkPasswordStrength(string $pass,
                                          int $minimumLength): bool
    {
        $length = (strlen($pass) < $minimumLength); // longueur minimale
        $digit = preg_match("#[\d]#", $pass); // au moins un digit
        $special = preg_match("#[\W]#", $pass); // au moins un car. spécial
        $lower = preg_match("#[a-z]#", $pass); // au moins une minuscule
        $upper = preg_match("#[A-Z]#", $pass); // au moins une majuscule
        if (!$length || !$digit || !$special || !$lower || !$upper) return false;
        return true;
    }


    public function logoutUser(): void
    {
        session_start();
        session_unset();
        session_destroy();
    }




}
