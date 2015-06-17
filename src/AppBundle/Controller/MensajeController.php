<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mensaje;
use AppBundle\Form\Type\MensajeType;
use Proxies\__CG__\AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MensajeController extends Controller
{
    /**
     * @Route("/marcar_leidos", name="marcar_leidos")
     */
    public function marcarLeidosAction()
    {

        $user = $this->getUser();
        if(isset($_POST['marcar-leidos'])){
            $em = $this->getDoctrine()->getManager();
            $mensajes = $em->getRepository('AppBundle:Mensaje')
                ->findBy(array('usuario'=>$user));
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
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));


        return $this->render(':default/usuario:mensajes.html.twig', [
            'usuario' => $user,
            'mensajes' => $mensajes,
            'contadorMensajes' => count($mensajes),
            'noLeidos' => $noLeidos,
            'contadorNoLeidos' => count($noLeidos),
            'enviados' => $enviados,
            'contadorEnviados' => count($enviados),
            'formulario' => $formulario1,
            'mnl' => count($noLeidos),
            'nnl' => count($nnl)
        ]);
    }



}



