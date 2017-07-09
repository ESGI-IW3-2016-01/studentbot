<?php
/**
 *
 */

namespace AppBundle\Service;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Component\Config\Definition\Exception\Exception;

class UserService
{
    /**
     * @var UserRepository $repository
     */
    private $repository;

    /**
     * @var string
     */
    private $graphVersion;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * SchoolService constructor.
     * @param UserRepository $schoolRepository
     * @param $graphVersion
     * @param $accessToken
     * @param EntityManager $em
     */
    public function __construct(UserRepository $schoolRepository, $graphVersion, $accessToken, EntityManager $em)
    {
        $this->repository = $schoolRepository;
        $this->graphVersion = $graphVersion;
        $this->accessToken = $accessToken;
        $this->em = $em;
        $this->client = new Client(['base_uri' => 'https://graph.facebook.com/']);
    }

    /**
     * Check if user exists, create and update if not
     * @param $senderId
     * @return User
     * @internal param string $facebookId
     */
    public function handleUser($senderId)
    {
        $user = $this->repository
            ->findOneBy(['senderId' => $senderId]);

        if (!$user) {

            $userData = $this->getUserDataFromFacebook($senderId);
            $username = $userData['first_name'] . ' ' . $userData['last_name'];

            $user = $this->repository
                ->findOneBy(['usernameCanonical' => strtolower($username)]);

            if (!$user) {
                $user = new User();
                $user->setRoles(['ROLE_USER']);
                $user->setEnabled(true);
            }

            $user->setSenderId($senderId);
            $user->setFirstName($userData['first_name']);
            $user->setLastName($userData['last_name']);
            $user->setUsername($username);
            $user->setUsernameCanonical(strtolower($username));

            $this->em->persist($user);
            $this->em->flush();
        }

        return $user;
    }

    /**
     * @param $senderId string
     * @return array
     */
    public function getUserDataFromFacebook($senderId)
    {
        $uri = $this->graphVersion . '/' . $senderId;

        $query = [
            'query' => [
                'access_token' => $this->accessToken,
                'fields' => 'first_name,last_name,profile_pic,locale,timezone,gender'
            ]
        ];

        try {
            $response = $this->client->request(
                'GET',
                $uri,
                $query
            );

            if ($response->getBody()) {
                $json = json_decode((string)$response->getBody(), true);
                return $json;
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return [];
    }
}