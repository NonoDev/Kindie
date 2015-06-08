<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Route("/portada", name="portada")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->createQueryBuilder('p')
            ->setMaxResults(3)
            ->addOrderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
        ;
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll()
            ;
        return $this->render(':default:portada.html.twig', [
            'proyectos' => $proyectos,
            'generos' => $generos,
            'usuario' => $user
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



