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
        $formulario = $this->createForm(new UsuarioMoType(), $usuario);

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



        // FIN MENSAJES //

        return $this->render(':default/usuario:mensajes.html.twig', [
            'usuario' => $user,
            'mensajes' => $mensajes,
            'contadorMensajes' => count($mensajes),
            'noLeidos' => $noLeidos,
            'contadorNoLeidos' => count($noLeidos),
            'enviados' => $enviados,
            'contadorEnviados' => count($enviados)
        ]);
    }

    /**
     * @Route("/perfil", name="perfil")
     */
    public function perfilAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->findby(array('usuario' => $user->getId()));
        return $this->render(':default/usuario:perfil.html.twig',[
            'usuario' => $user,
            'proyectos' => $proyectos
        ]);
    }

    /**
     * @Route("/editarPerfil", name="editarPerfil")
     */
    public function editarPerfilAction(Request $peticion)
    {
        $user = $this->getUser();
        $usuario = new Usuario();
        // crear el formulario
        $formulario = $this->createForm(new UsuarioModificarType(), $usuario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()){





        }

        return $this->render(':default/usuario:editarPerfil.html.twig',[
            'usuario' => $user,
            'formulario' => $formulario->createView()
        ]);
    }

}



