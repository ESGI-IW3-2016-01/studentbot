<?php

namespace AppBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class EditProfileFormHandler
{
    protected $request;
    protected $em;
    protected $session;

    public function __construct(RequestStack $request, ObjectManager $em, Session $session)
    {
        $this->request = $request->getCurrentRequest();
        $this->em      = $em;
        $this->session = $session;
    }

    public function process(Form $form)
    {

        if ('POST' === $this->request->getMethod()) {
            $form->handleRequest($this->request);

            if ($form->isValid()) {
                $this->em->flush();

                $this->session->getFlashBag()->add('info', "Modification enregistré");

                return true;
            }

            $this->session->getFlashBag()->add('danger', "Une erreur est survenue lors de l'enregistrement");
        }
        
        
        return false;
    }
}
