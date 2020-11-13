<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumsRepository")
 * @Vich\Uploadable
 * @UniqueEntity("titre")
 */
class Albums
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="date" ,nullable=true)
     */
    private $DateSorties;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artiste", inversedBy="albums")
     */
    private $artiste;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255 ,nullable=true)
     */
    private $filename;

    /**
     * @var File|null
     * @vich\UploadableField(mapping="album_image", fileNameProperty="filename")
     */
    private $imageAlbum;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $update_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deezer_id;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $Idyoutube;



  

  
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSorties(): ?\DateTimeInterface
    {
        return $this->DateSorties;
    }

    public function setDateSorties(\DateTimeInterface $DateSorties): self
    {
        $this->DateSorties = $DateSorties;

        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get the value of filename
     *
     * @return  string|null
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string|null  $filename
     *
     * @return  self
     */ 
    public function setFilename(?string $filename)
    {
        $this->filename = $filename;

        return $this;
    }
    /**
     * Get the value of imageFile
     *
     * @return  File|null
     */ 
    public function getImageAlbum()
    {
        return $this->imageAlbum;
    }

    /**
     * Set the value of imageFile
     *
     * @param  File|null  $imageFile
     *
     * @return  self
     */ 
    public function setImageAlbum($imageAlbum)
    {
        $this->imageAlbum = $imageAlbum;
        if ($this->imageAlbum instanceof UploadedFile) {
            $this->update_at = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        }
        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }

    public function setUpdateAt(?\DateTimeInterface $update_at): self
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function getDeezerId(): ?int
    {
        return $this->deezer_id;
    }

    public function setDeezerId(?int $deezer_id): self
    {
        $this->deezer_id = $deezer_id;

        return $this;
    }

    public function getIdyoutube(): ?string
    {
        return $this->Idyoutube;
    }

    public function setIdyoutube(string $Idyoutube): self
    {
        $this->Idyoutube = $Idyoutube;

        return $this;
    }


   

   
   
}
