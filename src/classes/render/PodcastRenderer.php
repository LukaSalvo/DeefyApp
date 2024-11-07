<?php
namespace iutnc\deefy\render;
class PodcastRenderer extends AudioTrackRenderer{

  protected function renderCompact(): string {
      return "<div><strong>{$this->audio->titre}</strong> par {$this->audio->auteur} - {$this->audio->duree} secondes<audio controls><source src='{$this->audio->nom_fichier}' type='audio/mpeg'></audio></div>";
  }

  protected function renderLong(): string {
      return "<div><h1>{$this->audio->titre} </h1><p>Auteur: {$this->audio->auteur}</p><p>Date: {$this->audio->date}</p> <p>Genre: {$this->audio->genre}</p><p>Duree: {$this->audio->duree} seconds</p><audio controls><source src='{$this->audio->nom_fichier}' type='audio/mpeg'></audio></div>";
  }
}
?>