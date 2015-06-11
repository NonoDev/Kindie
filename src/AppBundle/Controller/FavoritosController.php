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
        /*$query = $em->getRepository('AppBundle:Favorito')
            ->createQueryBuilder('f')
            ->where('f.proyecto = :proyecto')
            ->andWhere('f.usuario = :usuario')
            ->setMaxResults(1)
            ->setParameters(['proyecto'=> $id, 'usuario'=> $user ])
            ->getQuery()
            ->getResult();
        ;*/
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



}



