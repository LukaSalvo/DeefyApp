<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action {
    public function execute(): string {
        $repo = DeefyRepository::getInstance();
        $tracks = $repo->findAllTracks();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['track_id'])) {
            try {
                $trackId = (int)$_POST['track_id'];
                $playlistId = $_SESSION['playlist_id'];
                $repo->addTrackToPlaylist($trackId, $playlistId);
                return $this->renderSuccessMessage($playlistId);
            } catch (\Exception $e) {
                return $this->renderErrorMessage();
            }
        } else {
            return $this->renderTrackList($tracks);
        }
    }

    private function renderSuccessMessage(int $playlistId): string {
        return "
        <div class='content'>
            <p class='success-msg'>Piste ajoutée avec succès à la playlist !</p>
            <a href='?action=playlist&playlist_id={$playlistId}' class='btn-primary create-playlist-btn'>Retour à ma playlist</a>
        </div>";
    }

    private function renderErrorMessage(): string {
        return "
        <div class='content'>
            <p class='error-msg'>Erreur : Piste déjà ajoutée</p>
            <a href='?action=playlist' class='btn-primary create-playlist-btn'>Retour à mes playlist</a>
        </div>";
    }

    private function renderTrackList(array $tracks): string {
        $formHtml = "<div class='content'><h2>Ajouter une piste à la playlist</h2><div class='track-list'>";

        $_SESSION['playlist_id'] = $_POST['playlist_id'];

        foreach ($tracks as $track) {
            $formHtml .= <<<HTML
            <div class="track-item">
                <div class="track-info">
                    <p class="track-title">Titre: <strong>{$track->titre}</strong></p>
                    <p class="track-genre-duration">Genre: <span class="genre">{$track->genre}</span> | Durée: <span class="duration">{$track->duree} sec</span></p>
                </div>
                <form method="post" action="?action=add-track" class="add-track-form">
                    <input type="hidden" name="track_id" value="{$track->id}">
                    <input type="hidden" name="playlist_id" value="{$_POST['playlist_id']}">
                    <button type="submit" class="btn-primary">Ajouter</button>
                </form>
            </div>
            HTML;
        }

        $formHtml .= "</div></div>";
        return $formHtml;
    }
}
