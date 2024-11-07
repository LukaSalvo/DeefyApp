<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SigninAction extends Action {
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleFormSubmission();
        }
        return $this->renderForm();
    }

    private function renderForm(): string {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Connexion</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="form-container">
                <h2>Connexion</h2>
                <form method="post" action="?action=signinAction">
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <button type="submit" class="btn-primary">Se connecter</button>
                </form>

                <p class="signup-text">Vous n'avez pas de compte ?</p>
                <a href="?action=registerAction" class="btn-secondary">Inscrivez-vous</a>
            </div>
        </body>
        </html>
        HTML;
    }

    private function handleFormSubmission(): string
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            return '<p class="error-msg">Tous les champs sont obligatoires.</p>';
        }
        $user_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $user_password = $_POST['password'];
        try {
            AuthnProvider::signin($user_email, $user_password);
            header('Location: ?action=playlist');
            exit();
        } catch (AuthnException $e) {
            return '<p class="error-msg">Identifiants incorrects.</p>';
        }
    }
}
