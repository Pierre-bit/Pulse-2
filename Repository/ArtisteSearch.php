<?php

namespace App\Repository;

class ArtisteSearch {

    /**
     * @var string|null
     */
    private $nomArtiste;

    /**
     * Get the value of nomArtiste
     *
     * @return  string|null
     */ 
    public function getNomArtiste()
    {
        return $this->nomArtiste;
    }

    /**
     * Set the value of nomArtiste
     *
     * @param  string|null  $nomArtiste
     *
     * @return  self
     */ 
    public function setNomArtiste($nomArtiste)
    {
        $this->nomArtiste = $nomArtiste;

        return $this;
    }
}