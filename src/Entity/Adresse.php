<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdresseRepository")
 */
class Adresse
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
    private $idUtilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ad_livraison;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ad_facturation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(int $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getAdLivraison(): ?string
    {
        return $this->ad_livraison;
    }

    public function setAdLivraison(string $ad_livraison): self
    {
        $this->ad_livraison = $ad_livraison;

        return $this;
    }

    public function getAdFacturation(): ?string
    {
        return $this->ad_facturation;
    }

    public function setAdFacturation(string $ad_facturation): self
    {
        $this->ad_facturation = $ad_facturation;

        return $this;
    }
}
