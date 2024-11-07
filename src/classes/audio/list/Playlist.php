<?php

namespace iutnc\deefy\audio\list;
class Playlist extends AudioList {



    public function __construct (string $nom) {
        parent::__construct($nom);

    }

    public function ajtPiste($piste){
        $this->pistes[$this->nbPistes] = $piste;
        $this->nbPistes ++;
        $this->duree += $piste->duree;
    }


    public function supPiste(int $indice){
        if($indice < $this->nbPistes && $indice > 0){
            $this->nbPistes -= 1;
            $this->duree -= $this->pistes[$indice]->duree;
            unset($this->pistes[$indice]);

        }
    }


    public function ajtPiste2( $piste){
        $this->pistes = array_unique(array_merge($this->pistes,$piste));
    }








}