<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prix")
     * @ORM\Column(type="integer")
     */
    private $user_Id;


    /**
     * @ORM\Column(type="text")
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     */
    private $prenom;

    /**
     * @ORM\Column(type="text")
     */
    private $civilite;

    /**
     * @ORM\Column(type="text")
     */
    private $entreprise;

    /**
     * @ORM\Column(type="text")
     */
    private $type_etablissement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status_inscription;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $tel;



    /**
     * @ORM\Column(type="string", length=14)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $num_tva;

    /**
     * @ORM\Column(type="string", length=800)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $date_inscription;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $etablissement_autre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getTypeEtablissement(): ?string
    {
        return $this->type_etablissement;
    }

    public function setTypeEtablissement(string $type_etablissement): self
    {
        $this->type_etablissement = $type_etablissement;

        return $this;
    }

       public function getUserId(): ?int
    {
        return $this->user_Id;
    }

    public function setUserId(int $user_Id): self
    {
        $this->user_Id = $user_Id;

        return $this;
    }

    public function getStatusInscription(): ?bool
    {
        return $this->status_inscription;
    }

    public function setStatusInscription(bool $status_inscription): self
    {
        $this->status_inscription = $status_inscription;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

 

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getNumtva(): ?string
    {
        return $this->num_tva;
    }

    public function setNumtva(string $num_tva): self
    {
        $this->num_tva = $num_tva;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }

    public function getEtablissementAutre(): ?string
    {
        return $this->etablissement_autre;
    }

    public function setEtablissementAutre(string $etablissement_autre): self
    {
        $this->etablissement_autre = $etablissement_autre;

        return $this;
    }

    public function __toString() {
    return $this->user_Id;
}
}
