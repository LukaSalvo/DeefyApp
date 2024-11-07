<?php

namespace iutnc\deefy\render;
class AudioListRenderer implements Renderer{

    private $audioList;

    public function __construct($audioList)
    {
        $this->audioList = $audioList;
    }


    public function render(int $selector): string
    {
        $res =  "<h1>Nom : {$this->audioList->nom}</h1>\n";

        foreach ($this->audioList->pistes as $value) {
            $renderValue = $value->getRenderer();
            $res .= $renderValue->render(Renderer::COMPACT) . "\n";
        }
        $res .= "<p>Nombre de pistes : {$this->audioList->nbPistes} </p>\n";
        $res .= "<p>Durée totale : {$this->audioList->dureeTotale} secondes </p>\n";

        return $res;
    }


}