<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compte
 *
 * @ORM\Table(name="compte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompteRepository")
 */
class Compte
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
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="solde_initial", type="float", scale=2)
     */
    private $soldeInitial;

    /**
     * @var float
     *
     * @ORM\Column(name="current_solde", type="float", scale=2)
     */
    private $currentSolde;

    /**
     * @var float
     *
     * @ORM\Column(name="solde_previsionnel", type="float", scale=2)
     */
    private $soldePrevisionnel;


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
     * @return Compte
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
     * @return float
     */
    public function getSoldeInitial()
    {
        return $this->soldeInitial;
    }

    /**
     * @param float $soldeInitial
     * @return Compte
     */
    public function setSoldeInitial($soldeInitial)
    {
        $this->soldeInitial = $soldeInitial;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentSolde()
    {
        return $this->currentSolde;
    }

    /**
     * @param float $currentSolde
     * @return Compte
     */
    public function setCurrentSolde($currentSolde)
    {
        $this->currentSolde = $currentSolde;
        return $this;
    }

    /**
     * @return float
     */
    public function getSoldePrevisionnel()
    {
        return $this->soldePrevisionnel;
    }

    /**
     * @param float $soldePrevisionnel
     * @return Compte
     */
    public function setSoldePrevisionnel($soldePrevisionnel)
    {
        $this->soldePrevisionnel = $soldePrevisionnel;
        return $this;
    }

}

