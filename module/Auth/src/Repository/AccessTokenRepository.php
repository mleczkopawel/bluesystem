<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 22:23
 */

namespace Auth\Repository;


use Auth\Entity\AccessToken;
use Auth\Entity\Client;
use Auth\Entity\User;
use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\AccessTokenInterface;

/**
 * Class AccessTokenRepository
 * @package Auth\Repository
 */
class AccessTokenRepository extends EntityRepository implements AccessTokenInterface {

    /**
     * @param string $oauth_token
     * @return array|null|object
     */
    public function getAccessToken($oauth_token) {
        $token = $this->findOneBy(['token' => $oauth_token]);
        if ($token) {
            $token = $token->toArray();
            $token['expires'] = $token['expires']->getTimestamp();
        }

        return $token;
    }

    /**
     * @param string $oauth_token
     * @param mixed $client_id
     * @param mixed $user_id
     * @param int $expires
     * @param null $scope
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setAccessToken($oauth_token, $client_id, $user_id, $expires, $scope = null) {
        $entityManager = $this->getEntityManager();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['clientIdentifier' => $client_id]);
        if ($user_id) {
            $user = $entityManager->getRepository(User::class)->find($user_id);
        } else {
            $user = null;
        }

        $token = AccessToken::fromArray([
            'token' => $oauth_token,
            'client' => $client,
            'user' => $user ? $user : null,
            'expires' => (new \DateTime())->setTimestamp($expires),
            'scope' => $scope,
            'dateAdd' => new \DateTime(),
            'dateEdit' => new \DateTime(),
            'user_id' => $user ? $user->getId() : null,
            'client_id' => $client->getId()
        ]);
        $entityManager->persist($token);
        $entityManager->flush();
    }
}