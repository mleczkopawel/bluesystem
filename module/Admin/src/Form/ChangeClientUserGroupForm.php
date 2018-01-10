<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.01.18
 * Time: 12:50
 */

namespace Admin\Form;


use Auth\Entity\ClientGroups;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Form;

/**
 * Class ChangeClientUserGroupForm
 * @package Admin\Form
 */
class ChangeClientUserGroupForm extends Form {

    /**
     *
     */
    const USER = 'user';

    /**
     *
     */
    const GROUP = 'group';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ChangeClientUserGroupForm constructor.
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
        $groups = $this->entityManager->getRepository(ClientGroups::class)->findAll();
        $valueOptions = [];
        foreach ($groups as $group) {
            $valueOptions[] = [
                'value' => $group->getId(),
                'label' => $group->getName(),
            ];
        }

        $this->add([
            'name' => self::USER,
            'type' => Hidden::class,
            'attributes' => [
                'class' => 'js-' . self::USER
            ]
        ]);

        $this->add([
            'name' => self::GROUP,
            'type' => Select::class,
            'options' => [
                'label' => 'Grupy',
                'value_options' => $valueOptions,
            ],
            'attributes' => [
                'id' => self::GROUP,
                'name' => self::GROUP,
                'class' => 'form-control select2',
                'required' => true,
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