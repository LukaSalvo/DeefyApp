<?php

namespace iutnc\deefy\audio\tracks;


class PodcastTrack extends AudioTrack {
    protected $date;

    public function __construct($titre, $nom_fichier,$auteur, $date , $duree , $genre) {
        parent::__construct($titre, $nom_fichier);
        $this->auteur = $auteur;
        $this->date = $date;
        $this->duree = $duree;
        $this->genre = $genre;
    }



}

