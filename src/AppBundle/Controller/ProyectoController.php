<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Desarrollo;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Multimedia;
use AppBundle\Entity\Proyecto;
use AppBundle\Form\Type\ActualizacionType;
use AppBundle\Form\Type\EditarDetalleType;
use AppBundle\Form\Type\ImagenType;
use AppBundle\Form\Type\MensajeType;
use AppBundle\Form\Type\ComentarioType;
use AppBundle\Form\Type\ProyectoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProyectoController extends Controller
{
    /**
     * @Route("/descubre_proyecto", name="descubre_proyectos")
     */
    public function descubreAction()
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
        $populares = $em->getRepository('AppBundle:Proyecto')
            ->createQueryBuilder('p')
            ->setMaxResults(3)
            ->addOrderBy('p.visitas', 'DESC')
            ->getQuery()
            ->getResult();
        dump($populares);
        dump($user);
        return $this->render(':default/proyecto:descubre.html.twig', [
            'generos' => $generos,
            'proyectos' => $proyectos,
            'populares' => $populares
        ]);
    }

    /**
     * @Route("/empieza", name="empieza")
     */
    public function empiezaAction()
    {
        return $this->render(':default/proyecto:empieza.html.twig');
    }

    /**
     * @Route("/eliminar_comentario", name="eliminar_comentario")
     */
    public function eliminarComentarioAction(Request $request)
    {
        if(isset($_POST['eliminar_coment'])){
            $cometario = $_POST['eliminar_coment'];
            $em = $this->getDoctrine()->getManager();
            $coment = $em->getRepository('AppBundle:Comentario')
                ->find($cometario);
            $em->remove($coment);
            $em->flush();

            $id = $coment->getProyecto()->getId();

            return new RedirectResponse(
                $this->generateUrl('proyecto', array('id'=>$id))
            );
        }

    }

    /**
     * @Route("/proyecto/{id}", name="proyecto")
     */
    public function proyectoAction(Request $request, Proyecto $id)
    {

        $user = $this->getUser();

        $comentario = new Comentario();

        //$id = $request->query->get('id');
        // crear el formulario
        $formulario = $this->createForm(new ComentarioType(), $comentario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id)
        ;
        // aumentar visitas
        $visitas = $proyecto->getVisitas();
        $proyecto->setVisitas($visitas+1);
        $em->persist($proyecto);
        $em->flush();
        dump($visitas);
        // diferencia de fechas
        $fechaInicio = new \DateTime();
        $fechaFin = $proyecto->getFechaFin();
        $diff= date_diff($fechaInicio,$fechaFin);


        // control del formulario de comentarios
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $comentario->setFecha(new \DateTime('now'));
            $comentario->setProyecto($proyecto);
            $comentario->setUsuario($user);
            $texto = $formulario->get('texto')->getData();
            $comentario->setTexto($texto);

            $em->persist($comentario);
            $em->flush();
        }

        // Obtener comentarios
        $em = $this->getDoctrine()->getManager();
        $comentarios = $em->getRepository('AppBundle:Comentario')
            ->findBy(array('proyecto' => $id), array('fecha' => 'DESC'))
        ;

        // Obtener actualizaciones
        $em = $this->getDoctrine()->getManager();
        $desarrollo = $em->getRepository('AppBundle:Desarrollo')
            ->findBy(array('proyecto' => $id), array('fechaActualizacion' => 'DESC'))
        ;


        // MENSAJES //
        $mensaje = new Mensaje();

        // crear el formulario
        $formulario1 = $this->createForm(new MensajeType(), $mensaje);

        // Procesar el formulario si se ha enviado con un POST
        $formulario1->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $usuarioMensaje = $em->getRepository('AppBundle:Usuario')
            ->find($proyecto->getUsuario());
        ;
        if ($formulario1->isSubmitted() && $formulario1->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $mensaje->setRemitente($user->getId());
            $mensaje->setLeido(false);
            $mensaje->setFecha(new \DateTime());
            $mensaje->setUsuario($usuarioMensaje);

            $em->persist($mensaje);
            $em->flush();

        }

        // FIN MENSAJES //
        dump($desarrollo);
        return $this->render(':default/proyecto:proyecto.html.twig', [
            'usuario' => $user,
            'comentarios' => $comentarios,
            'contador' => count($comentarios),
            'proyecto' => $proyecto,
            'formulario' => $formulario->createView(),
            'formularioMensaje' => $formulario1->createView(),
            'diferencia' => $diff->days,
            'desarrollo' => $desarrollo
        ]);
    }

    /**
     * @Route("/genero", name="genero")
     */
    public function generoAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $genero = $em->getRepository('AppBundle:Genero')
            ->find($id)
        ;
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findBy(array('generos' => $id));

        dump($id);
        return $this->render(':default/genero:genero.html.twig', [
            'genero' => $genero,
            'proyectos' => $proyectos
        ]);
    }

    /**
     * @Route("/nuevo_proyecto", name="nuevo_proyecto")
     */
    public function nuevo_proyectoAction(Request $request)
    {
        $proyecto = new Proyecto();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        // crear el formulario
        $formulario = $this->createForm(new ProyectoType(), $proyecto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll();
        if ($formulario->isSubmitted() && $formulario->isValid()) {



        }
        dump($user, $generos);
        return $this->render(':default/proyecto:nuevo_proyecto.html.twig', [
            'generos' => $generos,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/multimedia_proyecto", name="multimedia_proyecto")
     */
    public function multimediaAction(Request $request)
    {
        $id = $request->query->get('id');
        $imagen = new Multimedia();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id);
        // crear el formulario
        $formulario = $this->createForm(new ImagenType(), $imagen);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $imagen->setRuta('galeria.png');
            $imagen->setProyecto($proyecto);
            $em->persist($imagen);
            $em->flush();
        }
        dump($user);
        return $this->render(':default/proyecto:multimedia.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/desarrollo_proyecto", name="desarrollo_proyecto")
     */
    public function actualizacionAction(Request $request)
    {
        $id = $request->query->get('id');
        $act = new Desarrollo();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        // crear el formulario
        $formulario = $this->createForm(new ActualizacionType(), $act);
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id)
        ;
        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $act->setProyecto($proyecto);
            $act->setFechaActualizacion(new \DateTime());

            $em->persist($act);
            $em->flush();

        }
        dump($user);
        return $this->render(':default/proyecto:actualizacion.html.twig', [
            'formulario' => $formulario->createView(),
            'usuario' => $user,
            'proyecto' => $proyecto
        ]);
    }

    /**
     * @Route("/editarDetalle_proyecto", name="editarDetalle_proyecto")
     */
    public function editarDetalleAction(Request $request)
    {
        $id = $request->query->get('id');
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id)
        ;
        // crear el formulario
        $formulario = $this->createForm(new EditarDetalleType(), $proyecto);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();

            $em->persist($proyecto);
            $em->flush();

        }
        dump($user);
        return $this->render(':default/proyecto:editarDetalle.html.twig', [
            'formulario' => $formulario->createView(),
            'proyecto' => $proyecto
        ]);
    }
}



