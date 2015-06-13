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
    public function multimediaAction(Request $request, Proyecto $id)
    {
        $ruta_final = "uploads/img/";
        $tipos = array('image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif');
        $imagen = new Multimedia();
        if(isset($_POST['subir'])){
           if(isset($_FILES['upload'])){
               if(in_array($_FILES['upload']['type'], $tipos)){
                   if(move_uploaded_file($_FILES['upload']['tmp_name'], $ruta_final.$_FILES['upload']['name']));
                   dump("El archivo ". basename( $_FILES["upload"]["name"]). " ha sido subido con éxito");
                   $em = $this->getDoctrine()->getManager();
                   $imagen->setProyecto($id);
                   $imagen->setRuta("/".$ruta_final.$_FILES['upload']['name']);
                   $em->persist($imagen);
                   $em->flush();
               }
           }
        }
        return $this->render(':default/proyecto:multimedia.html.twig', [
                'proyecto' => $id
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
                    dump("El archivo ". basename( $_FILES["upload"]["name"]). " ha sido subido con éxito");
                    $em = $this->getDoctrine()->getManager();
                    $user->setImagen("/".$ruta_final.$_FILES['upload']['name']);
                    $em->persist($user);
                    $em->flush();
                }
            }
        }
        return $this->render(':default/usuario:imagen_perfil.html.twig');
    }


}



