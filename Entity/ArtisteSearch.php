<?php

namespace App\Entity;


class ArtisteSearch
{
   
    /**
     * @var string
     */
    private $nom;

    /**
     * Get the value of Nom
     *
     * @return  string
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of Nom
     *
     * @param  string  $Nom
     *
     * @return  self
     */ 
    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }
}
