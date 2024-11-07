<?php

namespace iutnc\deefy\audio\tracks;


class AlbumTrack extends AudioTrack {
    protected $album;
    protected $annee;
    protected $numero_piste;


    public function __construct($titre, $nom_fichier) {
        parent::__construct($titre, $nom_fichier);
    }


    public function __toString() {

        return "AlbumTrack " . parent::__toString() . json_encode($this);

    }

}


?>