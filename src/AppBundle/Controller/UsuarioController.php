<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsuarioController extends Controller
{
    /**
     * @Route("/nuevo_usuario", name="nuevo_usuario")
     */
    public function registroAction()
    {
        return $this->render(':default/usuario:registro.html.twig');
    }


}



