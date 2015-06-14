<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\Type\MensajeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MensajeController extends Controller
{
    /**
     * @Route("/marcar_leidos/{id}", name="marcar_leidos")
     */
    public function marcarLeidosAction(Mensaje $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('AppBundle:Usuario')
            ->find($id);
        dump($usuario);
        if(isset($_POST['marcar-leidos'])){
            $em = $this->getDoctrine()->getManager();
            $mensajes = $em->getRepository('AppBundle:Mensaje')
                ->findBy(array('usuario'=>$usuario));
            foreach($mensajes as $item){
                $item->setLeido(true);
                $em->persist($item);
                $em->flush();
            }


            return new RedirectResponse(
                $this->generateUrl('mensajes_usuario')
            );
        }

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
            ->findBy(array('usuario' => $user->getId(), 'leido' => true));
        ;
        // Mensajes no leidos
        $em = $this->getDoctrine()->getManager();
        $noLeidos = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('leido' => false, 'usuario' => $user->getId()));

        // Mensajes enviados
        $em = $this->getDoctrine()->getManager();
        $enviados = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('remitente' => $user->getId()));




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
            'formulario' => $formulario1,
            'mnl' => count($noLeidos)
        ]);
    }



}



