<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\Type\CuentaType;
use AppBundle\Form\Type\MensajeType;
use AppBundle\Form\Type\UsuarioModificarType;
use AppBundle\Form\Type\UsuarioType;
use AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\BrowserKit\Response;
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
            $usuario->setImagen('/uploads/perfiles/perfil_demo.jpg');

                $em->persist($usuario);
                $em->flush();
            return new RedirectResponse(
                $this->generateUrl('imagen_usuario')
            );

        }


        return $this->render(':default/usuario:registro.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
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
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        return $this->render(':default/usuario:perfil.html.twig',[
            'usuario' => $user,
            'proyectos' => $proyectos,
            'usuario_perfil' => $usuario_perfil,
            'formularioMensaje' => $formulario1->createView(),
            'proyectos_usuario_perfil' => $proyectos_usuario_perfil,
            'mnl' => count($mnl)
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
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        return $this->render(':default/usuario:editarPerfil.html.twig',[
            'usuario' => $user,
            'formulario' => $formulario->createView(),
            'mnl' => count($mnl)
        ]);
    }

    /**
     * @Route("/administracion", name="administracion")
     * @Security(expression="has_role('ROLE_ADMIN')")
     */
    public function administracionAction(Request $peticion)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AppBundle:Usuario')
            ->findAll();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findBy(array('esValido' => false));

        $comentarios = $em->getRepository('AppBundle:Comentario')
            ->findAll();

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        return $this->render(':default/usuario:administracion.html.twig',[
            'usuario' => $user,
            'usuarios' => $usuarios,
            'proyectos' => $proyectos,
            'comentarios' => $comentarios,
            'contador_proyectos' => count($proyectos),
            'mnl' => count($mnl)
        ]);
    }

    /**
     * @Route("/cuenta/{id}", name="cuenta")
     */
    public function cuentaAction(Request $peticion, Usuario $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('AppBundle:Usuario')
            ->find($id);
        // crear el formulario
        $formulario = $this->createForm(new CuentaType(), $usuario);
        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);
        // Si se ha enviado y el contenido es válido, guardar los cambios

        if ($formulario->isSubmitted() && $formulario->isValid()){
            // Guardar el mensaje en la base de datos
            $em = $this->getDoctrine()->getManager();
            $helper =  $password = $this->container->get('security.password_encoder');
            $usuario->setPass($helper->encodePassword($usuario, $usuario->getPass()));
            $em->persist($usuario);
            $em->flush();
        }

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));

        return $this->render(':default/usuario:cuenta.html.twig',[
            'usuario' => $user,
            'formulario' => $formulario->createView(),
            'mnl' => count($mnl)
        ]);
    }





}



