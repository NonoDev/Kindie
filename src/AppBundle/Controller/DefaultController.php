<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UserRepository;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="inicio")
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
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        return $this->render(':default:portada.html.twig', [
            'proyectos' => $proyectos,
            'generos' => $generos,
            'usuario' => $user,
            'mnl' => count($mnl)
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



