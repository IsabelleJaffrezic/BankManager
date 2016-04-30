<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 *
 * @ORM\Table(name="operation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OperationRepository")
 */
class Operation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var Compte
     * @ORM\ManyToOne(targetEntity="Compte")
     */
    private $compte;

    /**
     * @var Categorie
     * @ORM\ManyToOne(targetEntity="Categorie")
     */
    private $categorie;

    /**
     * @var ModePaiement
     * @ORM\ManyToOne(targetEntity="ModePaiement")
     */
    private $modePaiement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOperation", type="date")
     */
    private $dateOperation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateValeur", type="date", nullable=true)
     */
    private $dateValeur;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="float", scale=2)
     */
    private $montant;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Operation
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateOperation
     *
     * @param \DateTime $dateOperation
     *
     * @return Operation
     */
    public function setDateOperation($dateOperation)
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    /**
     * Get dateOperation
     *
     * @return \DateTime
     */
    public function getDateOperation()
    {
        return $this->dateOperation;
    }

    /**
     * Set dateValeur
     *
     * @param \DateTime $dateValeur
     *
     * @return Operation
     */
    public function setDateValeur($dateValeur)
    {
        $this->dateValeur = $dateValeur;

        return $this;
    }

    /**
     * Get dateValeur
     *
     * @return \DateTime
     */
    public function getDateValeur()
    {
        return $this->dateValeur;
    }

    /**
     * @return Compte
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * @param Compte $compte
     * @return Operation
     */
    public function setCompte($compte)
    {
        $this->compte = $compte;
        return $this;
    }

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Operation
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param int $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return ModePaiement
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * @param ModePaiement $modePaiement
     */
    public function setModePaiement($modePaiement)
    {
        $this->modePaiement = $modePaiement;
    }
    
}

