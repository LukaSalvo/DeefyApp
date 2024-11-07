<?php


namespace iutnc\deefy\audio\list;
use \iutnc\deefy\exception\InvalidPropertyNameException;
class AudioList
{

    protected string $nom;
    protected int $nbPistes;
    private float $dureeTotale;
    private int $id ;
    private $pistes = [];

    public function __construct(string $nomAlbum, $pistes = [])
    {
        $this->nom = $nomAlbum;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = $this->calculerDureePiste($pistes);


    }


    public function calculerDureePiste($pistes): int
    {
        $duree = 0;
        foreach ($pistes as $value) {
            $duree += $value->__get('duree');
        }
        return $duree;
    }

    public function __get(mixed $attributs): mixed
    {
        if (property_exists($this, $attributs)) {
            return $this->$attributs;
        } else {
            throw new InvalidPropertyNameException("$attributs : invalid property");
        }
    }



    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


}