<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:12
 */

namespace Auth\Filter;


use Auth\Form\UserRegisterForm;
use Auth\Validator\PasswordStrengthValidator;
use Auth\Validator\UserEmailExistsValidator;
use Auth\Validator\UserLoginExistsValidator;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Csrf;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

class UserRegisterFilter extends InputFilter {

    public function init() {
        $this->add([
            'name' => UserRegisterForm::LOGIN,
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 5,
                    ],
                ],
                [
                    'name' => UserLoginExistsValidator::class,
                ],
            ],
        ]);

        $this->add([
            'name' => UserRegisterForm::EMAIL,
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToLower::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 5,
                    ],
                ],
                [
                    'name' => UserEmailExistsValidator::class,
                ],
                [
                    'name' => EmailAddress::class,
                ],
            ],
        ]);

        $this->add([
            'name' => UserRegisterForm::PASSWORD,
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 8,
                    ],
                ],
                [
                    'name' => PasswordStrengthValidator::class,
                ]
            ],
        ]);

        $this->add([
            'name' => UserRegisterForm::REPASSWORD,
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StripNewlines::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 8,
                    ],
                ],
                [
                    'name' => Identical::class,
                    'options' => [
                        'token' => UserRegisterForm::PASSWORD,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => UserRegisterForm::CSRF,
            'required' => true,
            'validators' => [
                [
                    'name' => Csrf::class,
                    'options' => [
                        'messages' => [
                            Csrf::NOT_SAME => 'Przesłany formularz nie pochodzi z oczekiwanej strony',
                        ],
                    ],
                ],
            ],
        ]);
    }
}