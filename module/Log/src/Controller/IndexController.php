<?php
/**
 * @link      http://github.com/zendframework/Log for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Log\Controller;

use Doctrine\ORM\EntityManager;
use Log\Entity\Api12Log;
use Log\Service\EntityCreator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Log\Controller
 */
class IndexController extends AbstractActionController {
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var EntityCreator
     */
    private $entityCreator;

    /**
     * IndexController constructor.
     * @param EntityManager $entityManager
     * @param EntityCreator $entityCreator
     */
    public function __construct(EntityManager $entityManager,
                                EntityCreator $entityCreator) {
        $this->entityManager = $entityManager;
        $this->entityCreator = $entityCreator;
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
    public function indexAction(): ViewModel {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function addAction(): ViewModel {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function listAction(): ViewModel {
        return new ViewModel();
    }

    public function rebuildEntityAction(): ViewModel {
        $this->entityCreator->rebuildEntity('Api_api_api2', [['name' => 'naamee12', 'type' => 'string', 'nullable' => true], ['name' => 'teksty', 'type' => 'string', 'nullable' => true]]);
        $this->redirect()->toRoute('admin/log', ['action' => 'createEntity']);
    }

    public function createEntityAction(): ViewModel {
        $this->entityCreator->createEntity('Api_api_api2', [['name' => 'naamee12', 'type' => 'string', 'nullable' => true], ['name' => 'teksty', 'type' => 'string', 'nullable' => true]]);
    }
}
