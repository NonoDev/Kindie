<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comentario;
use AppBundle\Form\Type\ComentarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProyectoController extends Controller
{
    /**
     * @Route("/descubre_proyecto", name="descubre_proyectos")
     */
    public function descubreAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyectos = $em->getRepository('AppBundle:Proyecto')
            ->createQueryBuilder('p')
            ->setMaxResults(3)
            ->addOrderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
        ;
        $generos = $em->getRepository('AppBundle:Genero')
            ->findAll()
        ;
        $populares = $em->getRepository('AppBundle:Proyecto')
            ->createQueryBuilder('p')
            ->setMaxResults(3)
            ->addOrderBy('p.visitas', 'DESC')
            ->getQuery()
            ->getResult();
        dump($populares);
        dump($user);
        return $this->render(':default/proyecto:descubre.html.twig', [
            'generos' => $generos,
            'proyectos' => $proyectos,
            'populares' => $populares
        ]);
    }

    /**
     * @Route("/empieza", name="empieza")
     */
    public function empiezaAction()
    {
        return $this->render(':default/proyecto:empieza.html.twig');
    }

    /**
     * @Route("/proyecto", name="proyecto")
     */
    public function proyectoAction(Request $request)
    {
        $comentario = new Comentario();
        $user = $this->getUser();
        $id = $request->query->get('id');
        // crear el formulario
        $formulario = $this->createForm(new ComentarioType(), $comentario);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id)
        ;
        // aumentar visitas
        $visitas = $proyecto->getVisitas();
        $proyecto->setVisitas($visitas+1);
        $em->persist($proyecto);
        $em->flush();
        dump($visitas);
        // diferencia de fechas
        $fechaInicio = new \DateTime();
        $fechaFin = $proyecto->getFechaFin();
        $diff= date_diff($fechaInicio,$fechaFin);


        // control del formulario de comentarios
        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comentario->setFecha(new \DateTime('now'));
            $comentario->setProyecto($proyecto);
            $comentario->setUsuario($user);
            $texto = $formulario->get('texto')->getData();
            $comentario->setTexto($texto);

            $em->persist($comentario);
            $em->flush();
        }

        // Obtener comentarios
        $em = $this->getDoctrine()->getManager();
        $comentarios = $em->getRepository('AppBundle:Comentario')
            ->findBy(array('proyecto' => $id), array('fecha' => 'DESC'))
        ;
        dump($comentarios);
        return $this->render(':default/proyecto:proyecto.html.twig', [
            'comentarios' => $comentarios,
            'contador' => count($comentarios),
            'proyecto' => $proyecto,
            'formulario' => $formulario->createView(),
            'diferencia' => $diff->days
        ]);
    }

    /**
     * @Route("/genero", name="genero")
     */
    public function generoAction(Request $request)
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $genero = $em->getRepository('AppBundle:Genero')
            ->find($id)
        ;
        dump($id);
        return $this->render(':default/genero:genero.html.twig', [
            'genero' => $genero
        ]);
    }


}



