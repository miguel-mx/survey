<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Respuesta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Academico controller.
 *
 * @Route("academico")
 */
class AcademicoController extends Controller
{
    /**
     * Lists all respuestum entities.
     *
     * @Route("/correos", name="academico_correos")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');


        $em = $this->getDoctrine()->getManager();

        $academicos = $em->getRepository('AppBundle:Academico')->findAll();
        $i = 0;

        foreach($academicos as $academico) {

            if(!$academico->getToken()) {
                $academico->setToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));

                $em->persist($academico);
                $i++;
            }
            $em->flush();

            // Si no tiene respuesta enviar correo
            if(!$academico->getRespuesta()) {



            }
        }

        return $this->render('academico/correos.html.twig', array(
            'academicos' => $academicos,
            'iteraciones' => $i,
        ));
    }


}
