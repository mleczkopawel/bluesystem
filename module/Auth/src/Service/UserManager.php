<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 16:27
 */

namespace Auth\Service;


use Auth\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Class UserManager
 * @package Auth\Service
 */
class UserManager {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * UserManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUser(int $id): User {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        return $user;
    }

    /**
     * @param array $data
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addUser(array $data): User {
        $user = new User($data);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }


}