<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.11.17
 * Time: 09:20
 */

namespace Auth\Form;


use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class UserLoginForm
 * @package Auth\Form
 */
class UserLoginForm extends Form {

    /**
     *
     */
    const LOGIN = 'login';

    /**
     *
     */
    const PASSWORD = 'password';

    /**
     *
     */
    const REMEMBER_ME = 'rememberMe';

    /**
     *
     */
    const REDIRECT_URL = 'redirectUrl';

    /**
     *
     */
    const CSRF = 'csrf';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     * @var array
     */
    public $messageTemplates = [
        self::LOGIN => 'Login',
        self::PASSWORD => 'Hasło',
        self::REMEMBER_ME => 'Zapamiętaj mnie',
        self::CSRF => 'Sesja wygasła',
    ];

    /**
     * UserLoginForm constructor.
     * @param int|null|string $name
     * @param array $params
     */
    public function __construct($name, $params = []) {
        parent::__construct($name, $params);
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
                'label' => 'Login',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::LOGIN,
                'required' => true,
                'placeholder' => 'Nazwa użytkownika/email',
            ],
        ]);

        $this->add([
            'name' => self::PASSWORD,
            'type' => Password::class,
            'options' => [
                'label' => 'Hasło',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::PASSWORD,
                'required' => true,
                'placeholder' => 'Hasło użytkownika',
            ],
        ]);

        $this->add([
            'name' => self::REMEMBER_ME,
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Zapamiętaj mnie',
            ],
            'attributes' => [
                'class' => 'checkbox',
                'id' => self::REMEMBER_ME,
            ],
        ]);

        $this->add([
            'name' => self::REDIRECT_URL,
            'type' => Hidden::class,
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
                'value' => 'Zaloguj',
                'class' => 'btn btn-primary block full-width m-b',
                'id' => self::SUBMIT,
            ],
        ]);
    }
}