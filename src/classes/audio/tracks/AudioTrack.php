<?php

namespace iutnc\deefy\audio\tracks;
use \iutnc\deefy\exception as E;
class AudioTrack {
    protected $titre;
    protected $auteur;
    protected $genre;
    protected $duree;
    protected $nom_fichier;


    protected $id;

    public function __construct($titre, $nom_fichier) {
        $this->titre = $titre;
        $this->nom_fichier = $nom_fichier;
        $this->genre = '';
        $this->duree = 0;

    }

    public function __toString() {
        return json_encode($this);
    }

    public function __get(string $attribut): mixed{
        if(property_exists($this,$attribut)){
            return $this->$attribut;
        }
        throw new E\InvalidPropertyNameException("$attribut : invalid property");
    }

    public function __set(string $attribut,mixed $value){
        if($attribut === 'duree'){
            if($value>=0){
                $this->$attribut = $value;
            } else throw new E\InvalidPropertyValueException("$value < 0 :  invalid value");
        } elseif (property_exists($this,$attribut) && $attribut !='titre' && $attribut !='nom_fichier'){
            $this->$attribut = $value;
        }
        else throw new E\InvalidPropertyNameException("$attribut : invalid property");
    }


    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



}

