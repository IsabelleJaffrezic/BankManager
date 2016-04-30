<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 13/04/16
 * Time: 22:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieOperation
 *
 * @ORM\Table(name="categorie_operation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieOperationRepository")
 */
class CategorieOperation
{
    /**
     * @var Categorie
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categorie")
     */
    private $categorie;

    /**
     * @var Operation
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Operation")
     */
    private $operation;

    /**
     * @var float
     * @ORM\Column(type="decimal", scale=2)
     */
    private $montant;

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     * @return CategorieOperation
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param Operation $operation
     * @return CategorieOperation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param float $montant
     * @return CategorieOperation
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
        return $this;
    }
    
}