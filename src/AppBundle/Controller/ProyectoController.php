<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProyectoController extends Controller
{
    /**
     * @Route("/descubre_proyecto", name="descubre_proyectos")
     */
    public function descubreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findAll()
        ;
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll()
        ;
        return $this->render(':default/proyecto:descubre.html.twig', [
            'generos' => $generos,
            'proyectos' => $proyectos
        ]);
    }

    /**
     * @Route("/empieza", name="empieza")
     */
    public function empiezaAction()
    {
        return $this->render(':default/proyecto:empieza.html.twig');
    }

    /**
     * @Route("/proyecto", name="proyecto")
     */
    public function proyectoAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id)
        ;
        dump($proyecto);
        return $this->render(':default/proyecto:proyecto.html.twig', [
            'proyecto' => $proyecto
        ]);
    }

    /**
     * @Route("/genero", name="genero")
     */
    public function generoAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $genero = $em->getRepository('AppBundle:Genero')
            ->find($id)
        ;
        dump($id);
        return $this->render(':default/genero:genero.html.twig', [
            'genero' => $genero
        ]);
    }


}



