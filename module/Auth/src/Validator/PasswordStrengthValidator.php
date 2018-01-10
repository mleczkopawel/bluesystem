<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 20.11.17
 * Time: 15:55
 */

namespace Auth\Validator;


use Zend\Validator\AbstractValidator;

/**
 * Class PasswordStrengthValidator
 * @package Auth\Validator
 */
class PasswordStrengthValidator extends AbstractValidator {

    /**
     *
     */
    const DIGITS = 'digits';

    /**
     *
     */
    const UPPER_CASE = 'upperCase';

    /**
     *
     */
    const LOWER_CASE = 'lowerCase';

    /**
     *
     */
    const SPECIAL_CHARS = 'specialChars';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::DIGITS => 'Brak cyfr',
        self::UPPER_CASE => 'Brak dużych znaków',
        self::LOWER_CASE => 'Brak małych znaków',
        self::SPECIAL_CHARS => 'Brak znaków specjalnych',
    ];

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value) {
        $isValid = true;


        if (!preg_match('/[A-Z]/', $value)) {
            $isValid = false;
            $this->error(self::UPPER_CASE);
        }

        if (!preg_match('/[0-9]/', $value)) {
            $isValid = false;
            $this->error(self::DIGITS);
        }

        if (!preg_match('/[a-z]/', $value)) {
            $isValid = false;
            $this->error(self::LOWER_CASE);
        }

        if (!preg_match('/[\W]+/', $value)) {
            $isValid = false;
            $this->error(self::SPECIAL_CHARS);
        }

        return $isValid;
    }
}