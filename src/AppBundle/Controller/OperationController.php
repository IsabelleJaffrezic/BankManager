<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 09/04/16
 * Time: 19:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Categorie;
use AppBundle\Entity\Compte;
use AppBundle\Entity\Operation;
use AppBundle\Form\CategorieFormType;
use AppBundle\Form\FilterOperationFormType;
use AppBundle\Form\OperationFormType;
use AppBundle\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class OperationController
 * @package AppBundle\Controller
 */
class OperationController extends Controller
{
    /**
     * @Route("/{compte}/operations")
     * @Template()
     *
     * @param Request $request
     * @param Compte $compte
     * @return mixed
     */
    public function indexAction(Request $request, Compte $compte)
    {
        $filterCategorie = $request->get('categorie', null);
        $filterLibelle = $request->get('libelle', null);

        $filter = [
            'categorie' => $filterCategorie,
            'libelle' => $filterLibelle,
        ];

        $operationsAPointer = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Operation')
            ->findAPointerByCompte($compte, $filter);

        $operationsPointees = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Operation')
            ->findPointeeByCompte($compte, $filter);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            array_merge($operationsAPointer, $operationsPointees),
            $request->query->getInt('page', 1),
            15
        );

        $categorie = $filterCategorie !== null ? $this->get('doctrine')->getRepository('AppBundle:Categorie')->find($filterCategorie) : null;
        $data = ['categorie' => $categorie, 'libelle' => $filterLibelle];
        $filterForm = $this->get('form.factory')
            ->createNamedBuilder(null, FormType::class, $data, ['method' => 'GET', 'csrf_protection' => false])
            ->add('categorie', EntityType::class, [
                'required' => false,
                'class' => 'AppBundle\Entity\Categorie',
                'placeholder' => 'Pas de filtre',
                'group_by' => 'parent',
                'query_builder' => function(CategorieRepository $repository) {
                    return $repository->findByParentNotNull();
                }])
            ->add('libelle', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Libellé...'
                ]
            ])
            ->add('filter', SubmitType::class, ['label' => 'Filtrer'])
            ->getForm()
        ;

        return [
            'pagination' => $pagination,
            'compte' => $compte,
            'formFilter' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/{compte}/operation/nouvelle")
     * @Template()
     *
     * @param Request $request
     * @param Compte $compte
     * @return array|RedirectResponse
     */
    public function postAction(Request $request, Compte $compte)
    {
        $operation = new Operation();
        $operation->setCompte($compte);
        $operation->setDateOperation(new \DateTime());

        $form = $this->createForm(OperationFormType::class, $operation);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->persist($operation);
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Opération créée');

                return $this->redirect($this->generateUrl('app_operation_index', ['compte' => $compte->getId()]));
            }
        }

        return [
            'form' => $form->createView(),
            'compte' => $compte,
        ];
    }

    /**
     * @Route("/operation/{operation}")
     * @Template()
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Operation $operation
     * @return array|RedirectResponse
     */
    public function putAction(Request $request, Operation $operation)
    {
        $form = $this->createForm(OperationFormType::class, $operation);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Opération modifiée');

                return $this->redirect($this->generateUrl('app_operation_index', ['compte' => $operation->getCompte()->getId()]));
            }
        }

        return [
            'form' => $form->createView(),
            'compte' => $operation->getCompte(),
            'operation' => $operation,
        ];
    }

    /**
     * @Route("/operation/{operation}")
     * @Method({"DELETE"})
     * @Template()
     *
     * @param Request $request
     * @param Operation $operation
     * @return array
     */
    public function deleteAction(Request $request, Operation $operation)
    {
        $form = $this
            ->createFormBuilder()
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, ['attr' => ['class' => 'btn btn-danger']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $doctrine = $this->get('doctrine.orm.default_entity_manager');
            $doctrine->remove($operation);
            $doctrine->flush();

            $this->get('session')->getFlashBag()->add('success', 'Opération supprimée');

            return $this->redirect($this->generateUrl('app_operation_index', ['compte' => $operation->getCompte()->getId()]));
        }

        return [
            'form' => $form->createView()
        ];
    }
}