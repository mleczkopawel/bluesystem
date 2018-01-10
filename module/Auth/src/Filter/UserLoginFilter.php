<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.11.17
 * Time: 09:20
 */

namespace Auth\Filter;


use Auth\Form\UserLoginForm;
use Auth\Validator\UserNotExistsValidator;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\Filter\StripNewlines;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Csrf;
use Zend\Validator\InArray;
use Zend\Validator\StringLength;

/**
 * Class UserLoginFilter
 * @package Auth\Filter
 */
class UserLoginFilter extends InputFilter {
    /**
     *
     */
    public function init() {
        $this->add([
            'name' => UserLoginForm::LOGIN,
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
                    'name' => UserNotExistsValidator::class,
                ],
            ],
        ]);

        $this->add([
            'name' => UserLoginForm::PASSWORD,
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
            ],
        ]);

        $this->add([
            'name' => UserLoginForm::CSRF,
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

        $this->add([
            'name' => UserLoginForm::REMEMBER_ME,
            'required' => false,
            'validators' => [
                [
                    'name' => InArray::class,
                    'options' => [
                        'haystack' => [0, 1],
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => UserLoginForm::REDIRECT_URL,
            'required' => false,
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
            ],
        ]);
    }
}