<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Respuesta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Respuestum controller.
 *
 * @Route("respuesta")
 */
class RespuestaController extends Controller
{
    /**
     * Lists all respuestum entities.
     *
     * @Route("/", name="respuesta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $respuestas = $em->getRepository('AppBundle:Respuesta')->findAll();
        $academicos = $em->getRepository('AppBundle:Academico')->findAll();

        $si = 0;
        $no = 0;
        $na = 0;

        foreach($academicos as $academico) {

            $r = $academico->getRespuesta();

            if($r) {
                if($r->getRespuesta()) {
                    $si++;
                }
                else {
                    $no++;
                }
            }
            else {
                $na++;
            }
        }

        return $this->render('respuesta/index.html.twig', array(
            'respuestas' => $respuestas,
            'si' => $si,
            'no' => $no,
            'na' => $na,
        ));
    }

    /**
     * Creates a new respuesta entity.
     * @Route("/{slug}/{token}/new", name="respuesta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $slug, $token)
    {

        $now = new \DateTime();
        $deadline = new \DateTime('2018-12-07T18:41');
        if($now >= $deadline)
            return $this->render(':respuesta:closed.html.twig');

        $em = $this->getDoctrine()->getManager();
        $academico = $em->getRepository('AppBundle:Academico')->findOneBySlug($slug);

        if($academico->getRespuesta()) {

            $this->addFlash(
                'alert',
                'Solo es posible participar una vez!'
            );

            return $this->redirectToRoute('respuesta_index');
        }

        if($academico->getToken() != $token) {
//            throw new BadRequestHttpException("Token no válido");
            throw $this->createNotFoundException('Token inválido');
//            throw $this->createBadRequestHttpException('Token no válido!');
        }

        $respuesta = new Respuesta();

        $form = $this->createForm('AppBundle\Form\RespuestaType', $respuesta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $respuesta->setAcademico($academico);

            $em->persist($respuesta);
            $em->flush();

            $this->addFlash(
                'notice',
                'Hemos recibido su opinión!'
            );

            return $this->redirectToRoute('respuesta_index');
        }

        return $this->render('respuesta/new.html.twig', array(
            'academico' => $academico,
            'respuesta' => $respuesta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a respuestum entity.
     *
     * @Route("/{id}", name="respuesta_show")
     * @Method("GET")
     */
    public function showAction(Respuesta $respuestum)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $deleteForm = $this->createDeleteForm($respuestum);

        return $this->render('respuesta/show.html.twig', array(
            'respuestum' => $respuestum,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing respuestum entity.
     *
     * @Route("/{id}/edit", name="respuesta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Respuesta $respuestum)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');


        $deleteForm = $this->createDeleteForm($respuestum);
        $editForm = $this->createForm('AppBundle\Form\RespuestaType', $respuestum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('respuesta_edit', array('id' => $respuestum->getId()));
        }

        return $this->render('respuesta/edit.html.twig', array(
            'respuestum' => $respuestum,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a respuestum entity.
     *
     * @Route("/{id}", name="respuesta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Respuesta $respuestum)
    {
        $form = $this->createDeleteForm($respuestum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($respuestum);
            $em->flush();
        }

        return $this->redirectToRoute('respuesta_index');
    }

    /**
     * Creates a form to delete a respuestum entity.
     *
     * @param Respuesta $respuestum The respuestum entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Respuesta $respuestum)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('respuesta_delete', array('id' => $respuestum->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
