<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action {

    public function execute(): string {
        $isUserLoggedIn = isset($_SESSION['user']);
        $buttonAction = $isUserLoggedIn ? '?action=playlist' : '?action=signinAction';
        $buttonText = $isUserLoggedIn ? 'Accédez à votre espace' : 'Commencez dès maintenant';

        return '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bienvenue sur Deefy</title>
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <div class="container">
                <h1>Bienvenue sur <span class="deefy-title">Deefy</span></h1>
                <p class="introduction">
                    Plongez dans le monde de la musique avec <strong>Deefy</strong>!
                    <br>Découvrez, écoutez et organisez vos playlists préférées.
                </p>
                <div class="features">
                    <div class="feature-item">
                        <h3>Créez vos playlists</h3>
                        <p>Organisez vos morceaux préférés dans des playlists personnalisées.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Explorez notre bibliothèque</h3>
                        <p>Accédez à des milliers de morceaux et découvrez de nouveaux artistes.</p>
                    </div>
                    <div class="feature-item">
                        <h3>Connectez-vous</h3>
                        <p>Accédez à vos playlists et à vos morceaux préférés où que vous soyez.</p>
                    </div>
                </div>
                <a href="' . $buttonAction . '" class="btn-primary">' . $buttonText . '</a>
            </div>
        </body>
        </html>';
    }
}
