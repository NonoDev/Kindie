<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Favorito;
use AppBundle\Entity\Proyecto;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FavoritosController extends Controller
{
    /**
     * @Route("/favoritos/{id}", name="favoritos")
     */
    public function favoritosAction(Proyecto $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $favorito = $em->getRepository('AppBundle:Favorito')
            ->findOneBy(array('proyecto' => $id, 'usuario' => $user));

        if($favorito != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($favorito);
            $em->flush();
        }else{
            $nFavorito = new Favorito();
            $em = $this->getDoctrine()->getManager();
            $nFavorito->setProyecto($id);
            $nFavorito->setUsuario($user);
            $em->persist($nFavorito);
            $em->flush();
        }

        return new RedirectResponse(
            $this->generateUrl('proyecto', array('id'=>$id->getId()))
        );
    }


    /**
     * @Route("/listar_favoritos", name="listar_favoritos")
     */
    public function listar_favoritosAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $favoritos = $em->getRepository('AppBundle:Favorito')
            ->findBy(array('usuario' => $user));

        // mensajes no leidos
        $mnl = $em->getRepository('AppBundle:Mensaje')
            ->findBy(array('usuario' => $user, 'leido' => false));
        // notis no leídas
        $nnl = $em->getRepository('AppBundle:Notificacion')
            ->findBy(array('usuario' => $user, 'leida' => false));
        return $this->render(':default/usuario:favoritos.html.twig', [
            'favoritos' => $favoritos,
            'mnl' => count($mnl),
            'nnl' => count($nnl)
        ]);
    }


}



