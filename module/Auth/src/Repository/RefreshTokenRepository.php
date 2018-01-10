<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 23:30
 */

namespace Auth\Repository;


use Auth\Entity\Client;
use Auth\Entity\RefreshToken;
use Auth\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use OAuth2\Storage\RefreshTokenInterface;

/**
 * Class RefreshTokenRepository
 * @package Auth\Repository
 */
class RefreshTokenRepository extends EntityRepository implements RefreshTokenInterface {

    /**
     * @param $refresh_token
     * @return null|object
     */
    public function getRefreshToken($refresh_token) {
        $refreshToken = $this->findOneBy(['refresh_token' => $refresh_token]);
        if ($refreshToken) {
            $refreshToken = $refreshToken->toArray();
            $refreshToken['expires'] = $refreshToken['expires']->getTimestamp();
        }

        return $refreshToken;
    }

    /**
     * @param $refresh_token
     * @param $client_id
     * @param $user_id
     * @param $expires
     * @param null $scope
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null) {
        $entityManager = $this->getEntityManager();
        $client = $entityManager->getRepository(Client::class)->findOneBy(['clientIdentifier' => $client_id]);
        $user = $entityManager->getRepository(User::class)->find($user_id);

        $refreshToken = RefreshToken::fromArray([
            'refreshToken' => $refresh_token,
            'client' => $client,
            'user' => $user,
            'expires' => (new \DateTime())->setTimestamp($expires),
            'scope' => $scope,
            'dateAdd' => new \DateTime(),
            'dateEdit' => new \DateTime(),
            'client_id' => $client->getId(),
            'user_id' => $user->getId(),
        ]);

        try {
            $entityManager->persist($refreshToken);
        } catch (ORMException $e) {
            throw new ORMException($e->getMessage());
        }
        $entityManager->flush();
    }

    /**
     * @param $refresh_token
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unsetRefreshToken($refresh_token) {
        $entityManager = $this->getEntityManager();
        $refreshToken = $this->findOneBy(['refreshToken' => $refresh_token]);

        try {
            $entityManager->remove($refreshToken);
        } catch (ORMException $e) {
            throw new ORMException($e->getMessage());
        }
        $entityManager->flush();
    }
}