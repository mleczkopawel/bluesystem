<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 22:03
 */

namespace Auth\Repository;


use Auth\Entity\User;
use Doctrine\ORM\EntityRepository;
use OAuth2\Request;
use OAuth2\Storage\UserCredentialsInterface;

/**
 * Class UserRepository
 * @package Auth\Repository
 */
class UserRepository extends EntityRepository implements UserCredentialsInterface {

    /**
     * @param $username
     * @param $password
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function checkUserCredentials($username, $password) {
        $entityManager = $this->getEntityManager();
        $clientName = Request::createFromGlobals()->server['PHP_AUTH_USER'];

        $user = $this->findOneBy(['email' => $username]);
        if (!$user) {
          $user = $this->findOneBy(['login' => $username]);
        }
        if ($user && $user->getStatus() != User::STATUS_RETIRED) {
            $clients = $user->getClients();
            $accessFlag = false;
            foreach ($clients as $client) {
                if ($client->getClientIdentifier() == $clientName) {
                    $accessFlag = true;
                    break;
                }
            }

            if ($accessFlag) {
                $response = $user->verifyPassword($password);
                if ($response) {
                    $user->setDateLogin(new \DateTime());
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
                return $response;
            }
            else
                return false;
        }

        return false;
    }

    /**
     * @param string $username
     * @return array|false|null|object
     */
    public function getUserDetails($username) {
        $user = $this->findOneBy(['email' => $username]);
        if ($user) {
            $user = $user->toArray();
            $user['user_id'] = $user['id'];
        }

        return $user;
    }
}