<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Multimedia;
use AppBundle\Entity\Proyecto;
use AppBundle\Form\Type\ImagenType;
use Proxies\__CG__\AppBundle\Entity\Usuario;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ImagenController extends Controller
{
    /**
     * @Route("/multimedia_proyecto/{id}", name="multimedia_proyecto")
     */
    public function multimediaAction(Proyecto $id)
    {
        $user = $this->getUser();
        //redirección si no es el usuario
        if($id->getUsuario()->getId() != $user->getId()){
            if($user->getesAdmin() == false) {
                $this->addFlash('danger', 'Acceso denegado');
                return new RedirectResponse(
                    $this->generateUrl('inicio')

                );
            }
        }
        $ruta_final = "uploads/img/";
        $tipos = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
        $imagen = new Multimedia();
        if(isset($_POST['subir'])){
           if(isset($_FILES['upload'])){
               if(in_array($_FILES['upload']['type'], $tipos)){
                   if(move_uploaded_file($_FILES['upload']['tmp_name'], $ruta_final.$_FILES['upload']['name']));
                   $em = $this->getDoctrine()->getManager();
                   $imagen->setProyecto($id);
                   $imagen->setRuta("/".$ruta_final.$_FILES['upload']['name']);
                   $em->persist($imagen);
                   $em->flush();
                   $this->addFlash('success', 'Imagen añadida de forma correcta');
               }else{
                   $this->addFlash('danger', 'El archivo tiene que ser de tipo imagen.');
               }
           }
        }
        $em = $this->getDoctrine()->getManager();
        $multimedia = $em->getRepository('AppBundle:Multimedia')
            ->findBy(array('proyecto' => $id));

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:multimedia.html.twig', [
                'proyecto' => $id,
                'mnl' => count($mnl),
                'nnl' => count($nnl),
                'multimedia' => $multimedia
            ]);
    }


    /**
     * @Route("/imagen_usuario", name="imagen_usuario")
     */
    public function imagenPerfilAction()
    {
        $user = $this->getUser();
        $ruta_final = "uploads/perfiles/";
        $tipos = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
        if(isset($_POST['subir'])){
            if(isset($_FILES['upload'])){
                if(in_array($_FILES['upload']['type'], $tipos)){
                    if(move_uploaded_file($_FILES['upload']['tmp_name'], $ruta_final.$_FILES['upload']['name']));
                    $em = $this->getDoctrine()->getManager();
                    $user->setImagen("/".$ruta_final.$_FILES['upload']['name']);
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', 'Imagen modificada de forma correcta');
                }else{
                    $this->addFlash('danger', 'El archivo tiene que ser de tipo imagen.');
                }
            }
        }
        $em = $this->getDoctrine()->getManager();
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/usuario:imagen_perfil.html.twig', [
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/imagen_proyecto/{id}", name="imagen_proyecto")
     */
    public function imagenProyectoAction(Proyecto $id)
    {
        $user = $this->getUser();
        //redirección si no es el usuario
        if($id->getUsuario()->getId() != $user->getId()){
            if($user->getesAdmin() == false) {
                $this->addFlash('danger', 'Acceso denegado');
                return new RedirectResponse(
                    $this->generateUrl('inicio')

                );
            }
        }
        $ruta_final = "uploads/principales_proyectos/";
        $tipos = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
        if(isset($_POST['subir'])){
            if(isset($_FILES['upload'])){
                if(in_array($_FILES['upload']['type'], $tipos)){
                    if(move_uploaded_file($_FILES['upload']['tmp_name'], $ruta_final.$_FILES['upload']['name']));
                    $em = $this->getDoctrine()->getManager();
                    $id->setImagenPrincipal("/".$ruta_final.$_FILES['upload']['name']);
                    $em->persist($id);
                    $em->flush();
                    $this->addFlash('success', 'Imagen modificada de forma correcta');
                }else{
                    $this->addFlash('danger', 'El archivo tiene que ser de tipo imagen.');
                }
            }
        }
        $em = $this->getDoctrine()->getManager();
        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/proyecto:imagenProyecto.html.twig', [
            'mnl' => count($mnl),
            'proyecto' => $id,
            'nnl' => count($nnl)
        ]);
    }

    /**
     * @Route("/eliminar_imagen/{id}", name="eliminar_imagen")
     */
    public function eliminarImagenAction(Multimedia $id)
    {
        if(isset($_POST['eliminar-ima'])){
            $em = $this->getDoctrine()->getManager();
            $imagen = $em->getRepository('AppBundle:Multimedia')
                ->find($id);
            $em->remove($imagen);
            $em->flush();
            $this->addFlash('success', 'Imagen eliminada de forma correcta');
            return new RedirectResponse(
                $this->generateUrl('multimedia_proyecto', array('id'=>$id->getProyecto()->getId()))
            );

        }


    }

}



