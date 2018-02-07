<?php
/**
 * @link      http://github.com/zendframework/Admin for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Admin\Form\FrontPageForm;
use Admin\Form\HeaderForm;
use Admin\Form\OfferForm;
use Auth\Entity\Client;
use Auth\Entity\Group;
use Auth\Entity\User;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Log\Entity\MainLog;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
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
     * @var array
     */
    private $config;

    /**
     * IndexController constructor.
     * @param EntityManager $entityManager
     * @param FlashMessengerService $flashMessengerService
     * @param array $config
     */
    public function __construct(EntityManager $entityManager,
                                FlashMessengerService $flashMessengerService,
                                array $config)
    {
        $this->_entityManager = $entityManager;
        $this->_flashMessengerService = $flashMessengerService;
        $this->config = $config;
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
        $clients = $this->_entityManager->getRepository(Client::class)->findAll();
        $groups = $this->_entityManager->getRepository(Group::class)->findAll();
        $users = $this->_entityManager->getRepository(User::class)->findAll();
        $logs = $this->_entityManager->getRepository(MainLog::class)->countDistinctLogs();

        $user = $this->_entityManager->getRepository(User::class)->findOneBy(['email' => $this->identity()['email']]);
        $userLastLogged = $this->_entityManager->getRepository(User::class)->findLastLogged();

        return new ViewModel([
            'messages' => $this->_flashMessengerService->getMessages(),
            'clients' => count($clients),
            'groups' => count($groups),
            'users' => count($users),
            'logs' => $logs,
            'user' => $user,
            'userLastLogged' => $userLastLogged[0],
        ]);
    }

    /**
     * @return string
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createSuperUserAction() {
        $user = $this->_entityManager->getRepository(User::class)->find(1);
        if (!$user) {
            $user = new User();
            $user->setLogin($this->config['login'])
                ->setEmail($this->config['email'])
                ->setPassword($this->config['password'])
                ->setStatus(1)
                ->setGroup(1)
                ->setDateAdd(new \DateTime());

            $this->_entityManager->persist($user);
            $this->_entityManager->flush();

            return 'Utworzono super użytkownika';
        } else {
            return 'Super użytkownik istnieje!';
        }
    }

    public function createUserAction() {
        $user = $this->_entityManager->getRepository(User::class)->find(2);
        if (!$user) {
            $user = new User();
            $user->setLogin('paml123')
                ->setEmail('mleczko.pawel1@gmail.com')
                ->setPassword('Titanum!9')
                ->setStatus(1)
                ->setGroup(1)
                ->setDateAdd(new \DateTime());

            $this->_entityManager->persist($user);
            $this->_entityManager->flush();

            return 'Utworzono super użytkownika';
        } else {
            return 'Super użytkownik istnieje!';
        }
    }
}
