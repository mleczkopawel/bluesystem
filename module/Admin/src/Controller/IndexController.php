<?php
/**
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Admin\Entity\Header;
use Admin\Form\FrontPageForm;
use Admin\Form\HeaderForm;
use Admin\Form\OfferForm;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends AbstractActionController {

    /**
     * @var EntityManager
     */
    private $_entityManager;

    /**
     * @var FlashMessengerService
     */
    private $_flashMessengerService;

    /**
     * IndexController constructor.
     * @param EntityManager $entityManager
     * @param FlashMessengerService $flashMessengerService
     */
    public function __construct(EntityManager $entityManager,
                                FlashMessengerService $flashMessengerService) {
        $this->_entityManager = $entityManager;
        $this->_flashMessengerService = $flashMessengerService;
    }

    /**
     * @param MvcEvent $mvcEvent
     * @return mixed
     */
    public function onDispatch(MvcEvent $mvcEvent) {
        $response = parent::onDispatch($mvcEvent);
        $this->layout()->setTemplate('admin/layout');
        return $response;
    }

    /**
     * @return ViewModel
     */
    public function indexAction() {
        $this->_flashMessengerService->setFlashMessenger($this->flashMessenger());

        return new ViewModel([
            'messages' => $this->_flashMessengerService->getMessages(),
        ]);
    }
}
