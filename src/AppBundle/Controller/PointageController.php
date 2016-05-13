<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Pointage;
use AppBundle\Entity\PointageOperation;
use AppBundle\Form\PointageFormType;
use AppBundle\Form\PointageOperationFormType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PointageController extends Controller
{
    /**
     * @Route("/pointages")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $pointageListe = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Pointage')
            ->findAll();

        return [
            'pointageListe' => $pointageListe,
        ];
    }

    /**
     * @Route("/pointage/nouveau")
     * @Method({"GET","POST"})
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function postAction(Request $request)
    {
        $pointage = new Pointage();

        $form = $this->createForm(PointageFormType::class, $pointage);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->persist($pointage);
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Pointage créé');

                return $this->redirect($this->generateUrl('app_pointage_index'));
            }
        }

        return [
            'form' => $form->createView(),
            'pointage' => $pointage,
        ];
    }

    /**
     * @Route("/pointage/{pointage}", requirements={"pointage":"\d+"})
     * @Method({"GET","PUT"})
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function putAction(Request $request, Pointage $pointage)
    {
        $form = $this->createForm(PointageFormType::class, $pointage, ['method' => 'PUT']);

        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $doctrine = $this->get('doctrine.orm.default_entity_manager');
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add('success', 'Pointage modifié');

                return $this->redirect($this->generateUrl('app_pointage_index'));
            }
        }

        return [
            'form' => $form->createView(),
            'pointage' => $pointage,
        ];
    }

    /**
     * @Route("/pointage/{pointage}", requirements={"pointage":"\d+"})
     * @Method({"DELETE"})
     * @Template()
     *
     * @param Request $request
     * @param Pointage $pointage
     * @return array
     */
    public function deleteAction(Request $request, Pointage $pointage)
    {
        $form = $this
            ->createFormBuilder()
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, ['attr' => ['class' => 'btn btn-danger']])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $doctrine = $this->get('doctrine.orm.default_entity_manager');
            $doctrine->remove($pointage);
            $doctrine->flush();

            $this->get('session')->getFlashBag()->add('success', 'Pointage supprimé');

            return $this->redirect($this->generateUrl('app_pointage_index'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/pointage/matching")
     * @Template()
     * @return array
     */
    public function matchingAction()
    {
        $operationList = $this
            ->get('doctrine')
            ->getRepository('AppBundle:Operation')
            ->findBy(['categorie' => null]);

        $pointageService = $this->get('app.service.pointage');

        $pointageList = [];
        $i = 0;
        foreach ($operationList as $operation) {
            $pointage = $pointageService->matching($operation);
            if ($pointage instanceof Pointage) {
                $pointageOperation = new PointageOperation();
                $pointageOperation->setCategorie($pointage->getCategorie());
                $pointageOperation->setOperation($operation);

                $pointageList[] = $this
                    ->createForm(PointageOperationFormType::class, $pointageOperation, ['action' => $this->generateUrl('app_pointage_pointage')])
                    ->createView();
                if ($i++ >= 4) {
                    break;
                }
            }
        }

        return [
            'pointageListForm' => $pointageList
        ];
    }

    /**
     * @Route("/pointage")
     * @Method({"POST"})
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function pointageAction(Request $request)
    {
        $doctrine = $this->get('doctrine');

        $operation = $doctrine->getRepository('AppBundle:Operation')->find($request->request->get('pointage_operation_form')['operation']);
        if (!($operation instanceof Operation)) {
            throw new Exception('Aucune opération sélectionnée');
        }

        $categorie = $doctrine->getRepository('AppBundle:Categorie')->find($request->request->get('pointage_operation_form')['categorie']);
        if (!($categorie instanceof Categorie)) {
            throw new Exception('Aucune catégorie sélectionnée');
        }

        $pointageOperation = new PointageOperation();
        $pointageOperation->setOperation($operation);
        $pointageOperation->setCategorie($categorie);

        $form =  $this->createForm(PointageOperationFormType::class, $pointageOperation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $pointageService = $this->get('app.service.pointage');
            $pointageService->pointer($pointageOperation);

            $this->get('session')->getFlashBag()->add('success', 'Pointage effectué');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Pointage raté');
        }

        return $this->redirect($this->generateUrl('app_pointage_index'));
    }
}
