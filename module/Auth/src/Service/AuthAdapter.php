<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 17.11.17
 * Time: 23:27
 */

namespace Auth\Service;


use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

/**
 * Class AuthAdapter
 * @package Auth\Service
 */
class AuthAdapter implements AdapterInterface {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var
     */
    private $email;

    /**
     * @var
     */
    private $password;

    /**
     * AuthAdapter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Result
     */
    public function authenticate(): Result {
        $dataa = explode('@', $this->email);
        if (isset($dataa[1])) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $this->email]);
        } else {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => $this->email]);
        }

        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, [
                'Identity not found'
            ]);
        }

        if ($user->getStatus() == User::STATUS_RETIRED) {
            return new Result(Result::FAILURE, null, [
                'Status retierd'
            ]);
        }

        if (!$user->check($user->getPassword(), $this->password)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [
                'Invalid credential',
            ]);
        }

        return new Result(Result::SUCCESS, $user->toArray(), [
            'Authenticated successfully',
        ]);
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(string $password) {
        $this->password = $password;
    }
}