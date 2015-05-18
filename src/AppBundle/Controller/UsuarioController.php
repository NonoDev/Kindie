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
        return $this->render(':default:portada.html.twig');
    }

    /**
     * @Route("/entrar", name="usuario_entrar", methods={"GET"})
     */
    public function entrarAction()
    {
        $helper = $this->get('security.authentication_utils');
        return $this->render('entrada.html.twig',
            [
                'last_username' => $helper->getLastUsername(),
                'error'         => $helper->getLastAuthenticationError()
            ]);
    }
}



