<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Desarrollo;
use AppBundle\Entity\Proyecto;
use AppBundle\Form\Type\ActualizacionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DesarrolloController extends Controller
{
    /**
     * @Route("/eliminar_act/{id}", name="eliminar_act")
     */
    public function eliminarActualizacionAction(Desarrollo $id)
    {
        if(isset($_POST['eliminar_act'])){
            $em = $this->getDoctrine()->getManager();
            $act = $em->getRepository('AppBundle:Desarrollo')
                ->find($id);
            $em->remove($act);
            $em->flush();

            $miid = $act->getProyecto()->getId();

            return new RedirectResponse(
                $this->generateUrl('proyecto', array('id'=>$miid))
            );
        }

    }

    /**
     * @Route("/desarrollo_proyecto/{id}", name="desarrollo_proyecto")
     */
    public function actualizacionAction(Request $request, Proyecto $id)
    {
        $act = new Desarrollo();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        // crear el formulario
        $formulario = $this->createForm(new ActualizacionType(), $act);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $act->setProyecto($id);
            $act->setFechaActualizacion(new \DateTime());

            $em->persist($act);
            $em->flush();

        }

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:actualizacion.html.twig', [
            'formulario' => $formulario->createView(),
            'usuario' => $user,
            'proyecto' => $id,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }


    /**
     * @Route("/editarDesarrollo_proyecto/{id}", name="editar_desarrollo_proyecto")
     */
    public function editarDesarrolloAction(Request $request, Desarrollo $id)
    {
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        // crear el formulario
        $formulario = $this->createForm(new ActualizacionType(), $id);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();

            $em->persist($id);
            $em->flush();

        }

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:actualizacion.html.twig', [
            'formulario' => $formulario->createView(),
            'usuario' => $user,
            'proyecto' => $id->getProyecto(),
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

}



