<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 12/04/16
 * Time: 19:30
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Categorie;
use AppBundle\Form\CategorieFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    /**
     * @Route("/categorie")
     * @Template()
     */
    public function indexAction()
    {
        $categorieList = $this->get('app.service.category')->getTreeCategory();
        
        return [
            'categorieListe' => $categorieList,
        ];
    }

    /**
     * @Route("/categorie/nouvelle")
     * @Method({"GET","POST"})
     * @Template()
     * 
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request)
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieFormType::class, $categorie);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->persist($categorie);
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Catégorie créée');

                return $this->redirect($this->generateUrl('app_categorie_index'));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/categorie/{categorie}")
     * @Template()
     * @Method({"GET","POST"})
     *
     * @param Request $request
     * @param Categorie $categorie
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function putAction(Request $request, Categorie $categorie)
    {
        $form = $this->createForm(CategorieFormType::class, $categorie);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Catégorie modifiée');

                return $this->redirect($this->generateUrl('app_categorie_index'));
            }
        }

        return [
            'form' => $form->createView(),
            'categorie' => $categorie,
        ];
    }

    /**
     * @Route("/categorie/{categorie}")
     * @Template()
     * @Method({"DELETE"})
     * 
     * @param Request $request
     * @param Categorie $categorie
     * @return array|void
     */
    public function deleteAction(Request $request, Categorie $categorie)
    {
        $form = $this
            ->createFormBuilder()
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, ['attr' => ['class' => 'btn btn-danger']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $doctrine = $this->get('doctrine.orm.default_entity_manager');
            $doctrine->remove($categorie);
            $doctrine->flush();

            $this->get('session')->getFlashBag()->add('success', 'Catégorie supprimée');

            return $this->redirect($this->generateUrl('app_categorie_index'));
        }

        return [
            'form' => $form->createView(),
        ];
    }
}