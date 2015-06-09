<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\Type\MensajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class NotificacionController extends Controller
{
    /**
     * @Route("/notificaciones", name="notificaciones")
     */
    public function marcarLeidosAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $notificaciones = $em->getRepository('AppBundle:Notificacion')
            ->findAll();


        return $this->render(':default/usuario:notificaciones.html.twig', [
            'notificaciones' => $notificaciones
        ]);
    }



}



