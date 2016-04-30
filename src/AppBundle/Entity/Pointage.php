<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 16/04/16
 * Time: 21:09
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 *
 * @ORM\Table(name="pointage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PointageRepository")
 */
class Pointage
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
     * @ORM\Column(name="libelle", type="string")
     */
    private $libelle;

    /**
     * @var Categorie
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categorie")
     */
    private $categorie;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Pointage
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Pointage
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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
     * @return Pointage
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }
}