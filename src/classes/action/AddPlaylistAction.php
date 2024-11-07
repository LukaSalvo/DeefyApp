<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\list\Playlist;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action {
    public function execute(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['playlist_name'])) {
            $nomPlaylist = filter_var($_POST['playlist_name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $playlist = new Playlist($nomPlaylist);
            $repo = DeefyRepository::getInstance();

            $user = unserialize($_SESSION['user']);
            $userId = $user->getId();
            $repo->saveEmptyPlaylistForUser($nomPlaylist, $userId);

            $_SESSION['playlist'] = $playlist;
            header('Location: ?action=playlist');
            exit();
        } else {
            return <<<HTML
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Créer une Playlist</title>
                <link rel="stylesheet" href="style.css">
            </head>
            <body>
                <div class="form-container">
                    <h2>Créer une nouvelle playlist</h2>
                    <p class="description">
                        Donnez un nom à votre playlist et commencez à y ajouter vos morceaux préférés.
                        Organisez votre musique selon vos envies !
                    </p>
                    <form method="post" action="?action=add-playlist">
                        <div class="form-group">
                            <label for="playlist_name">Nom de la playlist :</label>
                            <input type="text" name="playlist_name" id="playlist_name" required placeholder="Nom...">
                        </div>
                        <button type="submit" class="btn-primary">Créer Playlist</button>
                    </form>
                    <a href="?action=playlist" class="btn-secondary return-button">Revenir à vos playlists</a>
                </div>
            </body>
            </html>
            HTML;
        }
    }
}
