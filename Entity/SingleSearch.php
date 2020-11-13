<?php

namespace App\Entity;

class SingleSearch
{

    /**
     * @var string
     */
    private $titre;


    /**
     * Get the value of Titre
     *
     * @return  string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @param  string  $Titre
     *
     * @return  self
     */
    public function setTitre(string $titre)
    {
        $this->titre = $titre;

        return $this;
    }
}
