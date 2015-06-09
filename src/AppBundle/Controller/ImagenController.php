<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Multimedia;
use AppBundle\Form\Type\ImagenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ImagenController extends Controller
{
    /**
     * @Route("/multimedia_proyecto", name="multimedia_proyecto")
     */
    public function multimediaAction(Request $request)
    {
        $id = $request->query->get('id');
        $imagen = new Multimedia();
        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $proyecto = $em->getRepository('AppBundle:Proyecto')
            ->find($id);
        // crear el formulario
        $formulario = $this->createForm(new ImagenType(), $imagen);

        // Procesar el formulario si se ha enviado con un POST
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $imagen->setRuta('galeria.png');
            $imagen->setProyecto($proyecto);
            $em->persist($imagen);
            $em->flush();
        }
        dump($user);
        return $this->render(':default/proyecto:multimedia.html.twig', [
            'formulario' => $formulario->createView()
        ]);
    }


}



