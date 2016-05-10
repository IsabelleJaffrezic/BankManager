<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Pointage;
use AppBundle\Form\PointageFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/pointage/{pointage}")
     * @Method({"GET","PUT"})
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function putAction(Request $request, Pointage $pointage)
    {
        $form = $this->createForm(PointageFormType::class, $pointage);

        if ($request->isMethod('POST')) {
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
     * @Route("/pointage/{pointage}")
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
}
