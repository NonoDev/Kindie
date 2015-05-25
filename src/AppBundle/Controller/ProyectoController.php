<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProyectoController extends Controller
{
    /**
     * @Route("/descubre_proyecto", name="descubre_proyectos")
     */
    public function descubreAction()
    {
        return $this->render(':default/proyecto:descubre.html.twig');
    }

    /**
     * @Route("/empieza", name="empieza")
     */
    public function empiezaAction()
    {
        return $this->render(':default/proyecto:empieza.html.twig');
    }


}



