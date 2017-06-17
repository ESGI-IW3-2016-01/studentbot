<?php
namespace AppBundle\Security\Core\User;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;
use FOS\UserBundle\Model\UserManagerInterface;
use Ath\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class MyOAuthProvider extends FOSUBUserProvider
{
    private $em;
    private $session;

    public function __construct(UserManagerInterface $userManager, Array $properties, ObjectManager $em, $session)
    {
        $this->em=$em;
        $this->session = $session;

        parent::__construct($userManager, $properties);
    }
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();

        // Updating user by source
        switch ($providerName) {
            case 'facebook':
                $user = $this->handleFacebookResponse($response);
                break;
        }
        
        if ($user) {
            $username = $user->getUsername();
            $realUser = $this->loadUserByUsername($username); // connexion du user
            if ($realUser) {
                $this->session->getFlashBag()->add('info', 'Vous êtes bien authentifié');
                return $realUser;
            }
        }
        $this->session->getFlashBag()->add('error', 'Vous n\'avez pas le droit de vous connecter à ce site');
        
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        $uniqueId = $response->getUsername();
        $user->addOAuthAccount($providerName, $uniqueId);

        $this->userManager->updateUser($user);
    }

    /**
     * Ad-hoc creation of user
     *
     * @param UserResponseInterface $response
     *
     * @return User
     */
    protected function createUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser();
        $this->updateUserByOAuthUserResponse($user, $response);

        // set default values taken from OAuth sign-in provider account
        if (null !== $email = $response->getEmail()) {
            $user->setEmail($email);
        }

        if (null === $this->userManager->findUserByUsername($response->getNickname())) {
            $user->setUsername($response->getNickname());
        }

        $user->setEnabled(true);

        return $user;
    }

    /**
     * Attach OAuth sign-in provider account to existing user
     *
     * @param FOSUserInterface      $user
     * @param UserResponseInterface $response
     *
     * @return FOSUserInterface
     */
    protected function updateUserByOAuthUserResponse(FOSUserInterface $user, UserResponseInterface $response)
    {
        $providerName = $response->getResourceOwner()->getName();
        var_dump($providerName);
        die('ok');
        $providerNameSetter = 'set'.ucfirst($providerName).'Id';
        $user->$providerNameSetter($response->getUsername());

        if(!$user->getPassword()) {
            // generate unique token
            $secret = md5(uniqid(rand(), true));
            $user->setPassword($secret);
        }

        return $user;
    }

    public function handleFacebookResponse($response) {
        // User is from Facebook : DO STUFF HERE \o/
        // All data from Facebook
        $token = $response->getAccessToken();
        $tabResponse = $response->getResponse();

        /*$facebookId =  $tabResponse['id']; // Facebook ID, e.g. 537091253102004
        $prenom = $tabResponse['first_name'];
        $nom = $tabResponse['last_name'];
        $gender = $tabResponse['gender'];*/

        $email = $response->getEmail();
        
        // search user in database
        $user = $this->userManager->findUserBy(
            array(
                'email' => $email
            )
        );

        if(!$user) {
            return null;
        }
 
        return $user;
    }
    
}