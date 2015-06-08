<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\Type\MensajeType;
use AppBundle\Form\Type\UsuarioModificarType;
use AppBundle\Form\Type\UsuarioType;
use AppBundle\Form\Type\UsuarioModificarTypeType;
use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
class UsuarioController extends Controller
{
    /**
     * @Route("/nuevo_usuario", name="nuevo_usuario")
     */
    public function registroAction(Request $peticion)
    {
        $usuario = new Usuario();
        // crear el formulario
        $formulario = $this->createForm(new UsuarioType(), $usuario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()){
            $usuario->setesAdmin(false);
            $usuario->setEsCreador(false);
            $usuario->setEsParticipante(false);
            // Guardar el usuario en la base de datos
                $em = $this->getDoctrine()->getManager();
                $helper =  $password = $this->container->get('security.password_encoder');
                $usuario->setPass($helper->encodePassword($usuario, $usuario->getPass()));

                $em->persist($usuario);
                $em->flush();



        }

        return $this->render(':default/usuario:registro.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
    }

    /**
     * @Route("/mensajes_usuario", name="mensajes_usuario")
     */
    public function mensajesAction(Request $peticion)
    {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        // Mensajes del usuario
        $mensajes = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user->getId()));
        ;
        // Mensajes no leidos
        $em = $this->getDoctrine()->getManager();
        $noLeidos = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('leido' => false, 'usuario' => $user->getId()));

        // Mensajes enviados
        $em = $this->getDoctrine()->getManager();
        $enviados = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('remitente' => $user->getId()));
        dump($noLeidos, $user);



        // MENSAJES //
        $mensaje = new Mensaje();

        // crear el formulario
        $formulario1 = $this->createForm(new MensajeType(), $mensaje);

        // Procesar el formulario si se ha enviado con un POST
        $formulario1->handleRequest($peticion);

        $em = $this->getDoctrine()->getManager();
        if ($formulario1->isSubmitted() && $formulario1->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $mensaje->setRemitente($user->getId());
            $mensaje->setLeido(false);
            $mensaje->setFecha(new \DateTime());
          ;

            $em->persist($mensaje);
            $em->flush();

        }

        // FIN MENSAJES //

        return $this->render(':default/usuario:mensajes.html.twig', [
            'usuario' => $user,
            'mensajes' => $mensajes,
            'contadorMensajes' => count($mensajes),
            'noLeidos' => $noLeidos,
            'contadorNoLeidos' => count($noLeidos),
            'enviados' => $enviados,
            'contadorEnviados' => count($enviados),
            'formulario' => $formulario1
        ]);
    }

    /**
     * @Route("/perfil", name="perfil")
     */
    public function perfilAction(Request $peticion)
    {
        $user = $this->getUser();
        $id = $peticion->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $usuario_perfil = $em->getRepository('AppBundle:Usuario')
            ->find($id);
        $proyectos_usuario_perfil = $em->getRepository('AppBundle:Proyecto')
            ->findby(array('usuario' => $usuario_perfil->getId()));
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findby(array('usuario' => $user->getId()));

        // MENSAJES //
        $mensaje = new Mensaje();

        // crear el formulario
        $formulario1 = $this->createForm(new MensajeType(), $mensaje);

        // Procesar el formulario si se ha enviado con un POST
        $formulario1->handleRequest($peticion);
        ;
        if ($formulario1->isSubmitted() && $formulario1->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $mensaje->setRemitente($user->getId());
            $mensaje->setLeido(false);
            $mensaje->setFecha(new \DateTime());
            $mensaje->setUsuario($usuario_perfil);

            $em->persist($mensaje);
            $em->flush();

        }

        // FIN MENSAJES //
        dump($usuario_perfil);
        return $this->render(':default/usuario:perfil.html.twig',[
            'usuario' => $user,
            'proyectos' => $proyectos,
            'usuario_perfil' => $usuario_perfil,
            'formularioMensaje' => $formulario1->createView(),
            'proyectos_usuario_perfil' => $proyectos_usuario_perfil
        ]);
    }

    /**
     * @Route("/editarPerfil/", name="editarPerfil")
     */
    public function editarPerfilAction(Request $peticion)
    {

        $user = $this->getUser();
        $id = $peticion->query->get('id');
        if($user->getId()!= $id || $id == null){
            // redireccionar a la portada
            return new RedirectResponse(
                $this->generateUrl('perfil')
            );
        }
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('AppBundle:Usuario')
            ->find($id);
        // crear el formulario
        $formulario = $this->createForm(new UsuarioModificarType(), $usuario);
        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);
        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();

            $em->persist($usuario);
            $em->flush();
        }
        dump($id);
        return $this->render(':default/usuario:editarPerfil.html.twig',[
            'usuario' => $user,
            'formulario' => $formulario->createView()
        ]);
    }

    /**
     * @Route("/administracion", name="administracion")
     */
    public function administracionlAction(Request $peticion)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AppBundle:Usuario')
            ->findAll();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findBy(array('esValido' => false));

        $comentarios = $em->getRepository('AppBundle:Comentario')
            ->findAll();
        return $this->render(':default/usuario:administracion.html.twig',[
            'usuario' => $user,
            'usuarios' => $usuarios,
            'proyectos' => $proyectos,
            'comentarios' => $comentarios,
            'contador_proyectos' => count($proyectos)
        ]);
    }


    public function devuelveRemitente($id){
        $em = $this->getDoctrine()->getManager();
        $remitente = $em->getRepository('AppBundle:Usuario')
            ->find($id);
        $ret = $remitente->getNombreUsuario();

        return $ret;
    }



}



