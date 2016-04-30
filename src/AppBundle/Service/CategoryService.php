<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 16/04/16
 * Time: 19:33
 */

namespace AppBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;

class CategoryService
{
    private $om;

    public function __construct(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
    }

    public function getTreeCategory()
    {
        $categoryList = $this->om->getRepository('AppBundle:Categorie')->findBy([], ['parent' => 'ASC']);

        $tree = [];
        foreach ($categoryList as $categorie) {
            if ($categorie->getParent() !== null) {
                $tree[$categorie->getParent()->getId()]['children'][] = $categorie;
            } else {
                $tree[$categorie->getId()] = [$categorie, 'children' => []];
            }
        }

        return $tree;
    }
}