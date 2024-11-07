<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth as A;
use iutnc\deefy\exception as E;

class RegisterAction extends Action
{
    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password_confirmation'];
            if ($password !== $passwordConfirmation) {
                return "<p class='error-msg'>Les mots de passe ne correspondent pas.</p>";
            }

            try {
                A\AuthnProvider::register($email, $password);
                header('Location: ?action=playlist');
                exit();
            } catch (E\AuthnException|\Exception $e) {
                return "<p class='error-msg'>Veuillez réessayer avec une adresse email différente.</p>";
            }
        }

        return $this->renderForm();
    }

    private function renderForm(): string
    {
        return <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Inscription</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="form-container">
                <h2>Inscription</h2>
                <form method="post" action="?action=registerAction">
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmez le mot de passe :</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn-primary">S'inscrire</button>
                </form>
                
                <p class="signup-text">Vous avez déjà un compte ?</p>
                <a href="?action=signinAction" class="btn-secondary">Connectez-vous</a>
            </div>
        </body>
        </html>
        HTML;
    }
}
