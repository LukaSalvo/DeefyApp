<?php
namespace iutnc\deefy\render;
class AlbumTrackRenderer extends AudioTrackRenderer{

    protected function renderCompact(): string {
        return "<div><h1><strong>{$this->audio->titre}</strong> </h1>
               par {$this->audio->auteur} - {$this->audio->duree} secondes\n
               <p><audio controls><source src='{$this->audio->nom_fichier}' type='audio/mp3'></audio></p></div>";
    }

    protected function renderLong(): string {
        return "<div><h1>{$this->audio->titre}</h1>
                <p>Artiste: {$this->audio->auteur}</p>
                <p>Album: {$this->audio->album}</p>
                <p>Année: {$this->audio->annee}</p>
                <p>Numéro: {$this->audio->numero_piste}</p>
                <p>Genre: {$this->audio->genre}</p>
                <p>Duree: {$this->audio->duree} secondes</p>
                <audio controls><source src='{$this->audio->nom_fichier}' type='audio/mp3'></audio></div>";
    }
}
?>
