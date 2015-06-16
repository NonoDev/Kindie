<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comentario;
use AppBundle\Entity\Notificacion;
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

    /**
     * @Route("/denunciar_comentario/{id}", name="denunciar_comentario")
     */
    public function denunciarComentarioAction(Comentario $id)
    {
        $user = $this->getUser();


        if(isset($_POST['denunciar_coment'])){
            $em = $this->getDoctrine()->getManager();
            $id->setDenunciado(true);
            $em->persist($id);
            $em->flush();

            // Mandar notificacion al creador del comentario
            $notificacion = new Notificacion();
            $em = $this->getDoctrine()->getManager();
            $notificacion->setUsuario($id->getUsuario());
            $notificacion->setDescripcion('Tu comentario realizado en el proyecto ' . $id->getProyecto()->getNombre() . ' ha sido reportado y está pendiente de moderación. Si no ha incumplido ninguna norma no tiene de que preocuparse. Recuerde respetar las normas de la comunidad. Un saludo de la moderación de Kindie.');
            $notificacion->setTipo('Rerporte de comentarios');
            $notificacion->setLeida(false);
            $notificacion->setFecha(new \DateTime());
            $em->persist($notificacion);
            $em->flush();

            return new RedirectResponse(
                $this->generateUrl('proyecto', array('id'=>$id->getProyecto()->getId()))
            );
        }

    }

    /**
     * @Route("/eliminar_comentario/{id}", name="eliminar_comentario")
     */
    public function eliminarComentarioAction(Comentario $id)
    {
        if(isset($_POST['eliminar_coment'])){
            $em = $this->getDoctrine()->getManager();
            $coment = $em->getRepository('AppBundle:Comentario')
                ->find($id);
            $em->remove($coment);
            $em->flush();

            $id = $coment->getProyecto()->getId();

            return new RedirectResponse(
                $this->generateUrl('proyecto', array('id'=>$id))
            );
        }

    }

}



