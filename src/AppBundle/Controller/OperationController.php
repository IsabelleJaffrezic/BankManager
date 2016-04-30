<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 09/04/16
 * Time: 19:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Compte;
use AppBundle\Entity\Operation;
use AppBundle\Form\OperationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $operationsAPointer = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Operation')
            ->findAPointerByCompte($compte);

        $operationsPointees = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Operation')
            ->findPointeeByCompte($compte);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            array_merge($operationsAPointer, $operationsPointees),
            $request->query->getInt('page', 1),
            15
        );

        return [
            'pagination' => $pagination,
            'compte' => $compte,
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