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
use Doctrine\ORM\Query\Parameter;
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
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:descubre.html.twig', [
            'generos' => $generos,
            'proyectos' => $proyectos,
            'populares' => $populares,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/empieza", name="empieza")
     */
    public function empiezaAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:empieza.html.twig', [
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }


    /**
     * @Route("/proyecto/{id}", name="proyecto")
     */
    public function proyectoAction(Request $request, Proyecto $id)
    {

        $user = $this->getUser();
        $comentario = new Comentario();

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
            $comentario->setDenunciado(false);
            $texto = $formulario->get('texto')->getData();
            $comentario->setTexto($texto);

            $em->persist($comentario);
            $em->flush();
            $this->addFlash('success', 'Nuevo comentario añadido');
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
            $nombreRemi = $em->getRepository('AppBundle:Usuario')
                ->find($user->getId());
            $mensaje->setNombreRemitente($nombreRemi->getNombreUsuario());
            $mensaje->setFecha(new \DateTime());
            $mensaje->setUsuario($usuarioMensaje);

            $em->persist($mensaje);
            $em->flush();

            $this->addFlash('success', 'Mensaje enviado de forma correcta');

        }

        // FIN MENSAJES //

        // galería
        $multimedia = $em->getRepository('AppBundle:Multimedia')
            ->findBy(array('proyecto' => $id));

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));

        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:proyecto.html.twig', [
            'usuario' => $user,
            'comentarios' => $comentarios,
            'contador' => count($comentarios),
            'proyecto' => $proyecto,
            'formulario' => $formulario->createView(),
            'formularioMensaje' => $formulario1->createView(),
            'diferencia' => $diff->days,
            'desarrollo' => $desarrollo,
            'participantes' => count($proyecto->getParticipantes()),
            'multimedia' => $multimedia,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
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
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));

        return $this->render(':default/genero:genero.html.twig', [
            'genero' => $genero,
            'proyectos' => $proyectos,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/nuevo_proyecto/{nombre}", name="nuevo_proyecto")
     */
    public function nuevo_proyectoAction($nombre)
    {
        $proyecto = new Proyecto();
        $user=$this->getUser();
        $ruta_final = "uploads/principales_proyectos/";
        $tipos = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
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
            $proyecto->setFechaFin(new \DateTime($_POST['fechaFin']));
            $proyecto->setDescripcion("");
            $proyecto->setMeta($_POST['meta']);

            if(isset($_FILES['imagen_destacada'])){
                if(in_array($_FILES['imagen_destacada']['type'], $tipos)){
                    if(move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $ruta_final.$_FILES['imagen_destacada']['name']));
                    dump("El archivo ". basename( $_FILES["imagen_destacada"]["name"]). " ha sido subido con éxito");
                    $em = $this->getDoctrine()->getManager();
                    $proyecto->setImagenPrincipal("/".$ruta_final.$_FILES['imagen_destacada']['name']);
                }
            }

            $em->persist($proyecto);
            $em->flush();
            $this->addFlash('success', 'El proyecto se ha creado de forma correcta, edite su campaña si lo desea o vaya a ver como luce su proyecto');

            return new RedirectResponse(
                $this->generateUrl('editarDetalle_proyecto', array('id'=>$proyecto->getId()))
            );



        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:nuevo_proyecto.html.twig', [
            'generos' => $generos,
            'mnl' => count($mnl),
            'nnl' => count($nnl),
            'proyecto' => $nombre
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
            $this->addFlash('success', 'Campaña editada de forma correcta');

        }elseif(($formulario->isSubmitted() && !$formulario->isValid())){
            $this->addFlash('danger', 'Error al modificar la campaña, compruebe bien los datos introducidos');

        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:editarDetalle.html.twig', [
            'formulario' => $formulario->createView(),
            'proyecto' => $proyecto,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
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
            $this->addFlash('success', 'Inversión realizada con éxito, recibirá un correco con la información de su transacción.');

            // Mandar notificacion al creador del proyecto
            $em = $this->getDoctrine()->getManager();
            $notificacion->setUsuario($id->getUsuario());
            $notificacion->setDescripcion('El usuario ' . $user->getNombreUsuario() . ' ha realizado una inversión en tu proyecto ' . $id->getNombre() . ' por valor de ' . $inversion->getCantidad() . '€.');
            $notificacion->setTipo('Inversión');
            $notificacion->setLeida(false);
            $notificacion->setFecha(new \DateTime());
            $em->persist($notificacion);
            $em->flush();

            $em = $this->getDoctrine()->getManager();
            $user->setEsParticipante(true);
            $em->persist($user);
            $em->flush();

            // correo electrónico al participante
            $message = \Swift_Message::newInstance()
                ->setSubject('Inversión en '.$id->getNombre())
                ->setFrom('kindieOficial@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // app/Resources/views/Emails/registration.html.twig
                        ':default/emails:emails.html.twig',
                        array('proyecto' => $id, 'usuario' => $user, 'inversion' => $inversion)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
        }
        $em = $this->getDoctrine()->getManager();
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:participar.html.twig', [
            'proyecto' => $id,
            'formulario' => $formulario->createView(),
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/proyectos_apoyados", name="proyectos_apoyados")
     */
    public function apoyadosAction()
    {
        $user=$this->getUser();

        $em = $this->getDoctrine()->getManager();
        $apoyados = $em->getRepository('AppBundle:Inversion')
            ->findBy(array('usuario' => $user));
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:proyectosApoyados.html.twig', [
            'apoyados' => $apoyados,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/buscador_proyectos", name="buscador_proyectos")
     */
    public function buscadorAction()
    {
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyectos = null;
        if(isset($_POST['buscar'])) {
            $proyectos = $em->getRepository('AppBundle:Proyecto')->createQueryBuilder('p')
                ->where('p.nombre LIKE :nombre')
                ->setParameter('nombre', '%' . $_POST['search'] . '%')
                ->getQuery()
                ->getResult();
        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:buscador.html.twig', [
            'proyectos' => $proyectos,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }


    /**
     * @Route("/editar_proyecto/{id}", name="editar_proyecto")
     */
    public function editarProyectoAction(Proyecto $id, Request $request)
    {
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
// crear el formulario
        $formulario = $this->createForm(new ProyectoType(), $id);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($id);
            $em->flush();

            $this->addFlash('success', 'Proyecto editado de forma correcta');
        }elseif($formulario->isSubmitted() && !$formulario->isValid()){
            $this->addFlash('danger', 'Error al modificar el proyecto, revise los datos');
        }
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:editarProyecto.html.twig', [
            'mnl' => count($mnl),
            'nnl' => count($nnl),
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/reportar_proyecto/{id}", name="reportar_proyecto")
     */
    public function reportarProyectoAction(Proyecto $id)
    {
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $mensaje = new Mensaje();
        // reportar proyecto
        if(isset($_POST['enviar'])){
            $em = $this->getDoctrine()->getManager();
            $id->setEsValido(false);
            $em->persist($id);
            $em->flush();
            $mensaje->setFecha(new \DateTime());
            $mensaje->setLeido(false);
            $mensaje->setUsuario($id->getUsuario());
            $mensaje->setRemitente($user->getId());
            $mensaje->setTipo('Reporte');
            $mensaje->setTexto('El usuario '.$user->getNombreUsuario().' ha reportado tu proyecto '. $id->getNombre() .' por el siguiente motivo: '. $_POST['motivo'] .'.
            Tu proyecto va a ser revisado para ver si incumple alguna de las normas de publicación de los proyectos. Disculpe las molestias.');

            $em->persist($mensaje);
            $em->flush();
            $this->addFlash('success', 'Proyecto reportado de forma correcta');
        }

        // eliminar proyecto

        if(isset($_POST['eliminar_pro'])){
            $em = $this->getDoctrine()->getManager();
            $em->remove($id);
            $em->flush();
            $mensaje->setFecha(new \DateTime());
            $mensaje->setLeido(false);
            $mensaje->setUsuario($id->getUsuario());
            $mensaje->setRemitente($user->getId());
            $mensaje->setTipo('Reporte');
            $mensaje->setTexto('Sentimos comunicarle que hemos decidido suspender su proyecto '.$id->getNombre().' por incumplir alguna de las normas de Kindie. Si desea
            recibir más información se puede poner en contacto con el equipo de Kindie a través de kindieOficial@gmail.com. Le recordamos que al ser un incumplimiento de las
            normas deberá usted hacerse cargo de los usuarios que le pidan el reintegro de sus inversiones, en caso de que existan. Un saludo y sentimos las molestias.');

            $em->persist($mensaje);
            $em->flush();

            $this->addFlash('success', 'Proyecto eliminado de forma correcta');
            return new RedirectResponse(
                $this->generateUrl('administracion')
            );
        }

        // validar proyecto

        if(isset($_POST['validar_pro'])){
            $id->setEsValido(true);
            $em->persist($id);
            $em->flush();
            $mensaje->setFecha(new \DateTime());
            $mensaje->setLeido(false);
            $mensaje->setUsuario($id->getUsuario());
            $mensaje->setRemitente($user->getId());
            $mensaje->setTipo('Reporte');
            $mensaje->setTexto('Nos alegra comunicarle que su proyecto '.$id->getNombre().' ya no se encuentra en moderación y ha sido validado. Lamentamos loas
            incovenientes que esto le haya poddido causar. Un saludo del equipo de Kindie.');

            $em->persist($mensaje);
            $em->flush();

            $this->addFlash('success', 'Proyecto validado de forma correcta');

            return new RedirectResponse(
                $this->generateUrl('administracion')
            );
        }

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:reportarProyectos.html.twig', [
            'proyecto' => $id,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/comprobar_proyecto", name="comprobar_proyecto")
     */
    public function comrpobarProyectoAction(Request $request)
    {
        $nombre = $request->query->get('nombre');
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->findBy(array('nombre' => $nombre));

        if($proyecto){
            return new RedirectResponse(
                $this->generateUrl('nuevo_proyecto', array('nombre'=> 'Si'))
            );
        }else{
            return new RedirectResponse(
                $this->generateUrl('nuevo_proyecto', array('nombre'=> 'No'))
            );
        }

    }
}



