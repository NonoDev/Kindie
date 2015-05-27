<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UsuarioType;
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
        $usuario->setesAdmin(false);
        // crear el formulario
        $formulario = $this->createForm(new UsuarioType(), $usuario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($peticion);

        // Si se ha enviado y el contenido es válido, guardar los cambios
        if ($formulario->isSubmitted() && $formulario->isValid()){
            // Guardar el usuario en la base de datos
                $em = $this->getDoctrine()->getManager();
                $helper =  $password = $this->container->get('security.password_encoder');
                $usuario->setPass($helper->encodePassword($usuario, $usuario->getPass()));

                $em->persist($usuario);
                $em->flush();
                $this->addFlash('success', 'Datos guardados correctamente');



        }

        return $this->render(':default/usuario:registro.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
    }


}



