<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Form\Type\EditProfileFormType;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends BaseController
{

    public function showAction()
    {
        return parent::showAction();
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $user);

        $formHandler = $this->container->get('form.handler.edit_profile');
        $formHandler->process($form);

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @Route("/etudiant/supprimer/photo", name="remove_my_photo")
     */
    public function removePhotoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $trad = $this->container->get('translator');

        $user = $this->getUser();

        $user->removePhoto();

        $user->setPhotoId(null);
        $user->setPhotoExtension(null);
        $user->setPhotoOriginalName(null);

        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', $trad->trans("flash_message_remove_photo", array(), 'messages'));

        return $this->redirect($this->generateUrl('fos_user_profile_edit'));
    }

    /**
     * @Route("/etudiant/load/form-profile", name="load_form_profile")
     */
    public function loadFormProfileAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $idSchool = $request->query->get('idSchool');
            
            $school = $em->getRepository('AppBundle:School')->find($idSchool);

            if($school == null)
            {
                return new Response("KO");
            }

            $user = $this->getUser();
            $user->setSchool($school);
            
            $form = $this->createForm(EditProfileFormType::class, $user);
            
            return $this->render('FOSUserBundle:Profile:load_edit.html.twig',array(
                'form' => $form->createView(),
                'user' => $user
            ));

        }

        return new Response("KO");
    }

}