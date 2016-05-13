<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 14/05/16
 * Time: 17:25
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $compteListe = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Compte')
            ->findAll();

        $compte = array_pop($compteListe);

        return $this->forward('AppBundle:Operation:index', ['compte' => $compte], $request->query->all());
    }
}