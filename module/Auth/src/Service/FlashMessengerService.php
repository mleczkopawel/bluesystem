<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 19.11.17
 * Time: 13:27
 */

namespace Auth\Service;


use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * Class FlashMessengerService
 * @package Auth\Service
 */
class FlashMessengerService {

    /**
     * @var FlashMessenger
     */
    private $flashMessenger;

    /**
     * @return array
     */
    public function getMessages() {
        $messages = [];

        if ($this->flashMessenger->hasSuccessMessages()) {
            $messages['success'] = $this->flashMessenger->getSuccessMessages();
        }

        if ($this->flashMessenger->hasInfoMessages()) {
            $messages['info'] = $this->flashMessenger->getInfoMessages();
        }

        if ($this->flashMessenger->hasWarningMessages()) {
            $messages['warning'] = $this->flashMessenger->getWarningMessages();
        }

        if ($this->flashMessenger->hasErrorMessages()) {
            $messages['error'] = $this->flashMessenger->getErrorMessages();
        }

        return $messages;
    }

    /**
     * @param FlashMessenger $flashMessenger
     */
    public function setFlashMessenger(FlashMessenger $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

}