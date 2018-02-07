<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 22:03
 */

namespace Auth\Repository;


use Auth\Entity\ClientsUsersGroups;
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
        if (!count($user)) {
          $user = $this->findOneBy(['login' => $username]);
        }
        if ($user && $user->getStatus() != User::STATUS_RETIRED) {
            $clientsUsersGroups = $entityManager->getRepository(ClientsUsersGroups::class)->findBy(['user' => $user]);
            $accessFlag = false;
            foreach ($clientsUsersGroups as $clientsUsersGroup) {
                if ($clientsUsersGroup->getClient()->getClientIdentifier() == $clientName) {
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
                    return 'good';
                } else {
                    return 'pass';
                }
            }
            else
                return 'access';
        }
        return 'status';
    }

    /**
     * @param string $username
     * @return array|false|null|object
     */
    public function getUserDetails($username) {
        $user = $this->findOneBy(['email' => $username]);
        if (!$user) {
            $user = $this->findOneBy(['login' => $username]);
        }
        if ($user) {
            $user = $user->toArray();
            $user['user_id'] = $user['id'];
        }

        return $user;
    }

    /**
     * @return array|mixed
     */
    public function findAll() {
        return $this->getEntityManager()->createQuery('SELECT u FROM ' . User::class . ' u WHERE u.id != 1')->getResult();
    }

    /**
     * @return mixed
     */
    public function findLastLogged() {
        return $this->getEntityManager()->createQuery('SELECT u FROM ' . User::class . ' u WHERE u.id != 1 ORDER BY u.dateLogin DESC')->setMaxResults(1)->getResult();
    }
}