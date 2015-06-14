<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comentario;
use AppBundle\Form\Type\ComentarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ComentarioController extends Controller
{
    /**
     * @Route("/editar_comentario/{id}", name="editar_comentario")
     */
    public function marcarLeidosAction(Comentario $id, Request $peticion)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        // crear el formulario
        $formulario1 = $this->createForm(new ComentarioType(), $id);

        // Procesar el formulario si se ha enviado con un POST
        $formulario1->handleRequest($peticion);

        if ($formulario1->isSubmitted() && $formulario1->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();

            $em->persist($id);
            $em->flush();

            return new RedirectResponse(
                $this->generateUrl('proyecto', array('id'=> $id->getProyecto()->getId()))
            );
        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:editarComentario.html.twig', [
            'formulario' => $formulario1->createView(),
            'mnl' => count($mnl),
            'nnl' => count($nnl),
            'proyecto' => $id->getProyecto()
        ]);
    }



}



