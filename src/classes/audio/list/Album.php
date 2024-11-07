<?php

namespace iutnc\deefy\audio\list;
class Album extends AudioList{

    private $dateDeSortie;

    private $artiste;


    public function __construct(string $nomListe ,$pistes,string $artiste){
        parent::__construct($nomListe,$pistes);
        $this->artiste = $artiste;
        $this->dateDeSortie = date_create();
    }

    public function __setArtiste(string $artiste){
        $this->artiste = $artiste;
    }

    public function __setDateDeSortie($dateDeSortie){
        $this->dateDeSortie = $dateDeSortie;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }


}