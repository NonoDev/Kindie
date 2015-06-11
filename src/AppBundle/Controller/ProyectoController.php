<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Desarrollo;
use AppBundle\Entity\Inversion;
use AppBundle\Entity\Mensaje;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Notificacion;
use AppBundle\Entity\Proyecto;
use AppBundle\Form\Type\ActualizacionType;
use AppBundle\Form\Type\EditarDetalleType;
use AppBundle\Form\Type\MensajeType;
use AppBundle\Form\Type\ComentarioType;
use AppBundle\Form\Type\ParticiparType;
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

        return $this->render(':default/proyecto:proyecto.html.twig', [
            'usuario' => $user,
            'comentarios' => $comentarios,
            'contador' => count($comentarios),
            'proyecto' => $proyecto,
            'formulario' => $formulario->createView(),
            'formularioMensaje' => $formulario1->createView(),
            'diferencia' => $diff->days,
            'desarrollo' => $desarrollo,
            'participantes' => count($proyecto->getParticipantes())
        ]);
    }

    /**
     * @Route("/genero", name="genero")
     */
    public function generoAction(Request $request)
    {
        $user = $this->getUser();
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $genero = $em->getRepository('AppBundle:Genero')
            ->find($id)
        ;
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findBy(array('generos' => $id));

        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findByLeido($user);
        dump($mnl);
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
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll();

        dump($_POST);
        if(isset($_POST['crear'])){
            $em = $this->getDoctrine()->getManager();
            $proyecto->setUsuario($user);
            $proyecto->setContribuciones(0);
            $proyecto->setFechaInicio(new \DateTime());
            $proyecto->setVisitas(0);
            $proyecto->setEsValido(0);
            $proyecto->setNombre($_POST['nombre']);
            $proyecto->setDescripcionCorta($_POST['descrpcion_corta']);
            $genero = $em->getRepository('AppBundle:Genero')
                ->findOneBy(array('nombre' => $_POST['genero'] ));
            $proyecto->setGeneros($genero);
            $proyecto->setRecompensa($_POST['recompensa']);
            $proyecto->setLocalizacion($_POST['localizacion']);
            $proyecto->setImagenPrincipal($_POST['imagen_destacada']);
            $proyecto->setFechaFin(new \DateTime($_POST['fechaFin']));
            $proyecto->setDescripcion("");
            $proyecto->setMeta($_POST['meta']);

            $em->persist($proyecto);
            $em->flush();

            return new RedirectResponse(
                $this->generateUrl('editarDetalle_proyecto', array('id'=>$proyecto->getId()))
            );



        }
        dump($user, $generos);
        return $this->render(':default/proyecto:nuevo_proyecto.html.twig', [
            'generos' => $generos
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
     * @Route("/editarDetalle_proyecto/{id}", name="editarDetalle_proyecto")
     */
    public function editarDetalleAction(Request $request, Proyecto $id)
    {
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

    /**
     * @Route("/participar_proyecto/{id}", name="participar_proyecto")
     */
    public function participarAction(Request $request, Proyecto $id)
    {
        $user=$this->getUser();
        $inversion = new Inversion();
        $notificacion = new Notificacion();
        // crear el formulario
        $formulario = $this->createForm(new ParticiparType(), $inversion);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();

            $inversion->setProyecto($id);
            $inversion->setUsuario($user);
            $inversion->setFecha(new \DateTime());
            $em->persist($inversion);
            $em->flush();

            // Añadir la cantidad a las contribuciones totales del proyecto
            $em = $this->getDoctrine()->getManager();
            $cont = $inversion->getCantidad() + $id->getContribuciones();
            $id->setContribuciones($cont);
            $participantes = $id->getParticipantes($user);
                if(count($participantes) == 0) {
                    $id->addParticipante($user);
                }
            $em->persist($id);
            $em->flush();

            // Mandar notificacion al creador del proyecto
            $em = $this->getDoctrine()->getManager();
            $notificacion->setUsuario($id->getUsuario());
            $notificacion->setDescripcion('El usuario ' . $user->getNombreUsuario() . ' ha realizado una inversión en tu proyecto ' . $id->getNombre() . ' por valor de ' . $inversion->getCantidad() . '€.');
            $notificacion->setTipo('Inversión');
            $notificacion->setLeida(false);
            $notificacion->setFecha(new \DateTime());
            $em->persist($notificacion);
            $em->flush();

            // Mandar notificacion al creador del proyecto
            $em = $this->getDoctrine()->getManager();
            $user->setEsParticipante(true);
            $em->persist($user);
            $em->flush();
        }

        dump($id->getParticipantes()->getValues());
        return $this->render(':default/proyecto:participar.html.twig', [
            'proyecto' => $id,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/proyectos_apoyados", name="proyectos_apoyados")
     */
    public function apoyadosAction()
    {
        $user=$this->getUser();



        return $this->render(':default/proyecto:proyectosApoyados.html.twig');
    }
}



