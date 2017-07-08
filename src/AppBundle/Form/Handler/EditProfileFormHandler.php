<?php

namespace AppBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface as Translator;

class EditProfileFormHandler
{
    protected $request;
    protected $em;
    protected $session;
    protected $translator;

    public function __construct(RequestStack $request, ObjectManager $em, Session $session,  Translator $translator)
    {
        $this->request = $request->getCurrentRequest();
        $this->em      = $em;
        $this->session = $session;
        $this->translator = $translator;
    }

    public function process(Form $form)
    {

        if ('POST' === $this->request->getMethod()) {
            $form->handleRequest($this->request);

            if ($form->isValid()) {
                $this->em->flush();

                $this->session->getFlashBag()->add('info', $this->translator->trans("flash_msg_edit_profil_success", array(), 'messages'));

                return true;
            }

            $this->session->getFlashBag()->add('danger', $this->translator->trans("flash_msg_edit_profil_error", array(), 'messages'));
        }
        
        return false;
    }
}
