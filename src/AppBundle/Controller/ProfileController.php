<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends BaseController
{

    public function showProfileAction(Request $request, $slug)
    {
        die('ok');
       /* $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $userToShow = $em->getRepository('AthUserBundle:User')->findOneBySlug($slug);
        
        if(!$userToShow)
            throw new NotFoundHttpException("Page introuvable");

        $followers =  $em->getRepository('AthUserBundle:User')->getLastFollowers($userToShow);

        $countFollowers = $em->getRepository('AthUserBundle:User')->countFollowers($userToShow);

        $amiFollows = $em->getRepository('AthUserBundle:User')->getAmiFollows($user);
        
        if ($userToShow->getIsCelebrite()) {
            $produits = $em->getRepository('AthMainBundle:Produit')->getMyProducts($userToShow);
        }
        else // on récupère les products de son comparateur
        {
            $comparateurProduits = $userToShow->getUserComparateurProduits();
            $produits = array();
            foreach ($comparateurProduits as $oneProduit) {
                $produits[] = $oneProduit;
            }
        }

        $noProduct = false;
        if(!$produits){
            // on récupère les 20 derniers produits
            $produits = $em->getRepository('AthMainBundle:Produit')->getLastProductsLimit();
            $noProduct = true;
        }

        // 10 derniers posts
        $posts = $em->getRepository('AthMainBundle:Post')->getMyLimitfeed($userToShow);

        $form = $this->createForm(new PostFormType());

        $tableau = array();
        
        
        if($flux = simplexml_load_file('http://www.lequipe.fr/rss/actu_rss.xml'))
        {
           $donnee = $flux->channel;
        
           //Lecture des données
        
           foreach($donnee->item as $valeur)
           {
              //Affichages des données
            if ($valeur->enclosure['url'] == "") continue;
           $tableau[] = ["link" => $valeur->link,
                        "image" => $valeur->enclosure['url'],
                        "title" => substr($valeur->title, 0, 45)."...",
                        "fulltitle" => $valeur->title,
                        "description" => $valeur->description,
                        "date" => date("d/m/Y", strtotime($valeur->pubDate))];
           }
        }else echo 'Erreur de lecture du flux RSS';

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user,
            'userToShow' => $userToShow,
            'followers' => $followers,
            'amiFollows' => $amiFollows,
            'countFollowers' => $countFollowers,
            'posts' => $posts,
            'form' => $form->createView(),
            'produits' => $produits,
            'noProduct' => $noProduct,
            'lequipe' => $tableau
        ));*/
    }
    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
        die('ok');
      /*  
        $user = $this->getUser();

        if($user->getStatutJuridiqueId() == 3)
            $form = $this->createForm(new EditProfileAssocType(), $user);
        else
            $form = $this->createForm(new EditProfileType(), $user);

        $formHandler = $this->container->get('ath.user.form.handler.edit_profile');
        
        // Enregistrement des modifications + setflash
        $formHandler->process($form);

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'canDemandeCelebrite' => $user->canDemandeCelebrite()
        ));*/
    }

    /*public function removePhotoAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $trad = $this->container->get('translator');

        $user = $this->getUser();

        $user->removePhoto();

        $user->setPhotoId(null);
        $user->setPhotoExtension(null);
        $user->setPhotoOriginalName(null);

        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', $trad->trans("profile.flash.photoSupprimer", array(), 'home'));

        return $this->redirect($this->generateUrl('fos_user_profile_edit'));
    }*/

}