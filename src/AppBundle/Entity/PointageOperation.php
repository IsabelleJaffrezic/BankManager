<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PointageOperation
 *
 * @ORM\Table(name="pointage_operation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PointageOperationRepository")
 */
class PointageOperation
{
    /**
     * @var Categorie
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pointage")
     */
    private $categorie;

    /**
     * @var Operation
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Operation")
     */
    private $operation;

    /**
     * @return Pointage
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Pointage $categorie
     * @return PointageOperation
     */
    public function setCategorie(Categorie $categorie)
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
     * @return PointageOperation
     */
    public function setOperation(Operation $operation)
    {
        $this->operation = $operation;

        return $this;
    }

}
