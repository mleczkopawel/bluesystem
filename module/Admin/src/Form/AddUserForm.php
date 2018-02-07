<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 28.01.18
 * Time: 11:55
 */

namespace Admin\Form;


use Auth\Entity\User;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class AddUserForm
 * @package Admin\Form
 */
class AddUserForm extends Form {

    /**
     *
     */
    const NAME = 'name';

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
    const GROUP = 'group';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     *
     */
    public function init() {
        $this->add([
            'name' => self::NAME,
            'type' => Text::class,
            'options' => [
                'label' => 'Login',
            ],
            'attributes' => [
                'id' => self::NAME,
                'class' => 'form-control',
                'name' => self::NAME,
                'placeholder' => 'Login użytkownika',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => self::EMAIL,
            'type' => Email::class,
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'id' => self::EMAIL,
                'class' => 'form-control',
                'name' => self::EMAIL,
                'placeholder' => 'Email użytkownika',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => self::PASSWORD,
            'type' => Password::class,
            'options' => [
                'label' => 'Hasło',
            ],
            'attributes' => [
                'id' => self::PASSWORD,
                'class' => 'form-control',
                'name' => self::PASSWORD,
                'placeholder' => 'Hasło użytkownika',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => self::GROUP,
            'type' => Select::class,
            'options' => [
                'label' => 'Grupa wewnętrzna',
                'value_options' => User::GROUPS_MAP,
                'empty_option' => 'Wybierz grupę',
            ],
            'attributes' => [
                'id' => self::GROUP,
                'class' => 'form-control select2',
                'name' => self::GROUP,
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => self::SUBMIT,
            'type' => Submit::class,
            'attributes' => [
                'id' => self::SUBMIT,
                'name' => self::SUBMIT,
                'value' => 'Zapisz',
                'class' => 'btn btn-primary pull-right',
            ],
        ]);
    }

}