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
        $noLeidas = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => 0));
        $em = $this->getDoctrine()->getManager();
        $notificaciones = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user));

        foreach($noLeidas as $noL){
            $em = $this->getDoctrine()->getManager();
            $noL->setLeida(true);

            $em->persist($noL);
            $em->flush();

        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/usuario:notificaciones.html.twig', [
            'notificaciones' => $notificaciones,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }



}



