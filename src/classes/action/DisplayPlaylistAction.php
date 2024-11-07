<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {
    private AuthnProvider $authnProvider;
    private DeefyRepository $repository;

    public function __construct() {
        parent::__construct();
        $this->authnProvider = new AuthnProvider();
        $this->repository = DeefyRepository::getInstance();
    }

    public function execute(): string {
        $user = unserialize($_SESSION['user']);
        $userId = $user->getId();
        $userMail = $user->email;

        if (isset($_GET['playlist_id'])) {
            $playlistId = (int)$_GET['playlist_id'];
            $playlist = $this->repository->getPlaylistById($playlistId);
            $tracks = $this->repository->findTracksByPlaylistId($playlistId);

            $output = "
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Playlist: {$playlist->nom}</title>
                <link rel='stylesheet' href='style.css'>
            </head>
            <body>
                <div class='content' style='padding: 20px; background-color: #1d1d1d;'>
                    <h1 style='color: #FFC0CB;'>Playlist: {$playlist->nom}</h1>
                    <ul class='track-list' style='list-style: none; padding: 0;'>";

            foreach ($tracks as $track) {
                $audioPath = $track->nom_fichier;

                $output .= "
                    <li class='track-item' style='padding: 15px; margin: 15px 0; background-color: #292929; border-radius: 8px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);'>
                        <div class='track-info' style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;'>
                            <p class='track-title' style='font-size: 16px; color: #FFC0CB; flex-grow: 2;'><strong>{$track->titre}</strong> - {$track->genre}</p>
                            <p class='track-duration' style='color: #ff8fa3; font-style: italic; font-size: 14px; flex-shrink: 0; margin-left: 10px;'>{$track->duree} sec</p>
                        </div>
                        <audio controls style='width: 100%; border-radius: 6px; outline: none;'>
                            <source src='{$audioPath}' type='audio/mpeg'>
                            Votre navigateur ne supporte pas le lecteur audio.
                        </audio>
                    </li>";
            }

            $output .= "
                    </ul>
                    <a href='?action=playlist' class='btn-primary create-playlist-btn'>Retour à mes playlists</a>
                </div>
            </body>
            </html>";

            return $output;
        }
        
        $playlists = $this->repository->findPlaylistsByUserId($userId);
        $output = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Mes Playlists</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>
            <div class='content'>
                <h1>Bienvenue, $userMail !</h1>
                <a href='?action=add-playlist' class='btn-primary create-playlist-btn'>Ajouter une nouvelle playlist</a>
                <p class='instruction-text'>Cliquez sur le nom d'une playlist pour accéder à son contenu.</p>
                <h2>Vos Playlists</h2>";
        
        if (empty($playlists)) {
            $output .= "<p>Aucune playlist trouvée.</p>";
        } else {
            foreach ($playlists as $playlist) {
                $output .= "<div class='playlist-item'>";
                $output .= "<h3><a href='?action=playlist&playlist_id={$playlist->id}'>{$playlist->nom}</a></h3>";
                $output .= "<form action='?action=add-track' method='POST'>
                    <input type='hidden' name='playlist_id' value='{$playlist->id}'>
                    <button type='submit' class='btn-secondary'>Ajouter un titre</button>
                </form></div>";
            }
        }

        $output .= "
            </div>
        </body>
        </html>";
    
        return $output;
    }
}
