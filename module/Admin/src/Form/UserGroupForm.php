<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 02.02.18
 * Time: 21:28
 */

namespace Admin\Form;


use Zend\Form\Element\Submit;
use Zend\Form\Form;

/**
 * Class UserGroupForm
 * @package Admin\Form
 */
class UserGroupForm extends Form {

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
            'name' => self::SUBMIT,
            'type' => Submit::class,
            'attributes' => [
                'id' => self::SUBMIT,
                'name' => self::SUBMIT,
                'class' => 'btn btn-primary pull-right',
                'value' => 'Zapisz',
            ],
        ]);
    }

}