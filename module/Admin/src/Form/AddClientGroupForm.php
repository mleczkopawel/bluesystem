<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.01.18
 * Time: 18:42
 */

namespace Admin\Form;


use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class AddClientGroupForm
 * @package Admin\Form
 */
class AddClientGroupForm extends Form {

    /**
     *
     */
    const NAME = 'name';

    /**
     *
     */
    const SUBMIT = 'submit';

    /**
     * AddClientGroupForm constructor.
     * @param null $name
     * @param array $options
     */
    public function __construct($name = null, array $options = []) {
        parent::__construct($name, $options);
    }


    /**
     *
     */
    public function init() {
        $this->add([
            'name' => self::NAME,
            'type' => Text::class,
            'options' => [
                'label' => 'Grupa',
            ],
            'attributes' => [
                'class' => 'form-control',
                'id' => self::NAME,
                'required' => false,
                'placeholder' => 'Nazwa grupy',
            ],
        ]);

        $this->add([
            'name' => self::SUBMIT,
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Zapisz grupę',
                'class' => 'btn btn-primary pull-right',
                'id' => self::SUBMIT,
            ],
        ]);
    }

}