<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produit;
use App\Entity\Utilisateur;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrixRepository")
 */
class Prix
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_utilisateur;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(Utilisateur $idUtilisateur): self
   {
       $id = $idUtilisateur->getUserId();
       $this->id_utilisateur = $id;

       return $this;
   }

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }

    public function setIdProduit(Produit $idProduit): self
   {
       $id = $idProduit->getId();
       $this->id_produit = $id;
       return $this;
   }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}