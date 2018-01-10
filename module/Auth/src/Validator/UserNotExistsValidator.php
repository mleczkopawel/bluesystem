<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.11.17
 * Time: 09:20
 */

namespace Auth\Validator;


use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Validator\AbstractValidator;

/**
 * Class UserNotExistsValidator
 * @package Auth\Validator
 */
class UserNotExistsValidator extends AbstractValidator {

    /**
     *
     */
    const USER_NOT_EXISTS_EMAIL = 'userNotExistsEmail';

    const USER_NOT_EXISTS_LOGIN = 'userNotExistsLogin';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::USER_NOT_EXISTS_EMAIL => 'Użytkownik o danym e-mailu: <strong>%value%</strong> nie istnieje',
        self::USER_NOT_EXISTS_LOGIN => 'Użytkownik o danym loginie: <strong>%value%</strong> nie istnieje',
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
    public function isValid($value) {
        $isValid = true;

        $vaalue = explode('@', $value);


        if (isset($vaalue[1])) {
            $user = $this->_entityManager->getRepository(User::class)->findOneBy(['email' => $value]);
            if (!$user) {
                $isValid = false;
                $this->error(self::USER_NOT_EXISTS_EMAIL, $value);
            }
        }

        if (!isset($vaalue[1])) {
            $user = $this->_entityManager->getRepository(User::class)->findOneBy(['login' => $value]);
            if (!$user) {
                $isValid = false;
                $this->error(self::USER_NOT_EXISTS_LOGIN, $value);
            }
        }

        return $isValid;
    }
}