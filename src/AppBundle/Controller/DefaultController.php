<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="portada")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findAll()
        ;
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll()
            ;
        //dump($generos);
        return $this->render(':default:portada.html.twig', [
            'proyectos' => $proyectos,
            'generos' => $generos
        ]);
    }

    /**
     *
     * @Route("/entrar", name="usuario_entrar")
     */
    public function entrarAction()
    {
        $helper = $this->get('security.authentication_utils');
        dump($helper->getLastAuthenticationError());
        return $this->render(':default/usuario:entrada.html.twig',
            [
                'last_username' => $helper->getLastUsername(),
                'error'         => $helper->getLastAuthenticationError()
            ]);
    }
}



