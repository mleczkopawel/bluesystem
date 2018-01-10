<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 22:11
 */

namespace Admin\Form;


use Auth\Entity\Client;
use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class AddClientAppForm
 * @package Admin\Form
 */
class AddClientAppForm extends Form {

    /**
     *
     */
    const CLIENT_NAME = 'name';

    /**
     *
     */
    const CLIENT_TYPE = 'type';

    /**
     *
     */
    const CLIENT_IDENTIFIER = 'client_identifier';

    /**
     *
     */
    const CLIENT_SECRET = 'client_secret';

    /**
     *
     */
    const REDIRECT_URI = 'redirect_uri';

    /**
     *
     */
    const USERS = 'users';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AddClientAppForm constructor.
     * @param null $name
     * @param array $options
     * @param EntityManager $entityManager
     */
    public function __construct($name = null, array $options = [], EntityManager $entityManager) {
        parent::__construct($name, $options);
        $this->entityManager = $entityManager;
    }


    /**
     *
     */
    public function init() {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        $valueOptionsUsers = [];
        foreach ($users as $user) {
            $valueOptionsUsers[] = [
                'value' => $user->getId(),
                'label' => $user->getViewName(),
            ];
        }

        $valueOptionsType = [];
        foreach (Client::CLIENT_TYPES as $key => $CLIENT_TYPE) {
            $valueOptionsType[] = [
                'value' => $key,
                'label' => $CLIENT_TYPE,
                'attributes' => [
                    'class' => 'radio',
                ],
            ];
        }

        $this->add([
            'name' => self::CLIENT_NAME,
            'type' => Text::class,
            'options' => [
                'label' => 'Nazwa',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::CLIENT_NAME,
                'required' => true,
                'placeholder' => 'Nazwa aplikacji',
            ],
        ]);

        $this->add([
            'name' => self::CLIENT_IDENTIFIER,
            'type' => Text::class,
            'options' => [
                'label' => 'Identyfikator',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::CLIENT_IDENTIFIER,
                'required' => false,
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => self::REDIRECT_URI,
            'type' => Text::class,
            'options' => [
                'label' => 'Redirect',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::REDIRECT_URI,
                'required' => false,
                'placeholder' => 'Redirect',
            ],
        ]);

        $this->add([
            'name' => self::CLIENT_SECRET,
            'type' => Text::class,
            'options' => [
                'label' => 'Secret',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::CLIENT_SECRET,
                'required' => false,
                'readonly' => true,
            ],
        ]);

        $this->add([
            'name' => self::CLIENT_TYPE,
            'type' => Radio::class,
            'options' => [
                'label' => 'Typ aplikacji',
                'value_options' => Client::CLIENT_TYPES,
            ],
        ]);

        $this->add([
            'name' => self::USERS,
            'type' => Select::class,
            'options' => [
                'label' => 'Użytkownicy',
                'value_options' => $valueOptionsUsers,
            ],
            'attributes' => [
                'id' => self::USERS,
                'name' => self::USERS,
                'class' => 'form-control chosen-select',
                'required' => false,
                'multiple' => true,
                'tabindex' => 4
            ],
        ]);

        $this->add([
            'name' => self::SUBMIT,
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Zapisz',
                'class' => 'btn btn-primary pull-right',
                'id' => self::SUBMIT,
            ],
        ]);
    }

}