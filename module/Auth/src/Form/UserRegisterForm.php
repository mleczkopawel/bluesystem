<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 18.11.17
 * Time: 15:13
 */

namespace Auth\Form;


use Zend\Form\Element\Csrf;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class UserRegisterForm
 * @package Auth\Form
 */
class UserRegisterForm extends Form {

    /**
     *
     */
    const LOGIN = 'login';

    /**
     *
     */
    const EMAIL = 'email';

    /**
     *
     */
    const PASSWORD = 'password';

    /**
     *
     */
    const REPASSWORD = 'repassword';

    /**
     *
     */
    const CSRF = 'csrf';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     * UserRegisterForm constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = []) {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'm-t');
    }

    /**
     *
     */
    public function init() {
        $this->add([
            'name' => self::LOGIN,
            'type' => Text::class,
            'options' => [
                'label' => 'Login'
            ],
            'attributes' => [
                'required' => true,
                'id' => self::LOGIN,
                'class' => 'form-control',
                'placeholder' => 'Nazwa użytkownika',
            ],
        ]);

        $this->add([
            'name' => self::EMAIL,
            'type' => Email::class,
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'required' => true,
                'id' => self::EMAIL,
                'class' => 'form-control',
                'placeholder' => 'Email użytkownika',
            ],
        ]);

        $this->add([
            'name' => self::PASSWORD,
            'type' => Password::class,
            'options' => [
                'label' => 'Hasło',
            ],
            'attributes' => [
                'required' => true,
                'id' => self::PASSWORD,
                'class' => 'form-control',
                'placeholder' => 'Hasło użytkownika',
            ],
        ]);

        $this->add([
            'name' => self::REPASSWORD,
            'type' => Password::class,
            'options' => [
                'label' => 'Powtórz hasło',
            ],
            'attributes' => [
                'required' => true,
                'id' => self::REPASSWORD,
                'class' => 'form-control',
                'placeholder' => 'Powtórz hasło użytkownika',
            ],
        ]);

        $this->add([
            'name' => self::CSRF,
            'type' => Csrf::class,
            'options' => [
                'csrf_options' => [
                    'timeout' => 600,
                ],
            ],
        ]);

        $this->add([
            'name' => self::SUBMIT,
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Zarejestruj',
                'class' => 'btn btn-primary btn-block',
                'id' => self::SUBMIT,
            ],
        ]);
    }
}