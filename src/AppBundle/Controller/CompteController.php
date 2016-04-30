<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CompteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/comptes")
     */
    public function indexAction(Request $request)
    {
        $compteListe = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Compte')
            ->findAll();

        // replace this example code with whatever you need
        return $this->render('AppBundle:Compte:index.html.twig', [
            'compteListe' => $compteListe,
        ]);
    }
}
