<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:23
 */

namespace Auth\Validator;


use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Validator\AbstractValidator;

/**
 * Class UserLoginExistsValidator
 * @package Auth\Validator
 */
class UserLoginExistsValidator extends AbstractValidator {

    /**
     *
     */
    const USER_EXISTS = 'userExists';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::USER_EXISTS => 'Użytkownik <strong>%value%</strong> istnieje',
    ];

    /**
     * @var EntityManager
     */
    private $_entityManager;

    /**
     * UserNotExistsValidator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->_entityManager = $entityManager;

        parent::__construct(null);
    }


    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $isValid = true;

        $user = $this->_entityManager->getRepository(User::class)->findOneBy(['login' => $value]);
        if ($user) {
            $isValid = false;
            $this->error(self::USER_EXISTS, $value);
        }

        return $isValid;
    }
}