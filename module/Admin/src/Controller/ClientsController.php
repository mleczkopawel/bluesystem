<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 22:45
 */

namespace Admin\Controller;


use Admin\Form\AddClientAppForm;
use Admin\Form\AddClientGroupForm;
use Admin\Form\ChangeClientUserGroupForm;
use Auth\Entity\Client;
use Auth\Entity\ClientGroups;
use Auth\Entity\User;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class ClientsController
 * @package Admin\Controller
 */
class ClientsController extends AbstractActionController {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AddClientAppForm
     */
    private $addClientAppForm;

    /**
     * @var FlashMessengerService
     */
    private $flashMessengerService;

    /**
     * @var ChangeClientUserGroupForm
     */
    private $changeClientUserGroupForm;

    /**
     * @var AddClientGroupForm
     */
    private $addClientGroupForm;

    /**
     * ClientsController constructor.
     * @param EntityManager $entityManager
     * @param AddClientAppForm $addClientApp
     * @param FlashMessengerService $flashMessengerService
     * @param ChangeClientUserGroupForm $changeClientUserGroupForm
     * @param AddClientGroupForm $addClientGroupForm
     */
    public function __construct(EntityManager $entityManager,
                                AddClientAppForm $addClientApp,
                                FlashMessengerService $flashMessengerService,
                                ChangeClientUserGroupForm $changeClientUserGroupForm,
                                AddClientGroupForm $addClientGroupForm) {
        $this->entityManager = $entityManager;
        $this->addClientAppForm = $addClientApp;
        $this->flashMessengerService = $flashMessengerService;
        $this->changeClientUserGroupForm = $changeClientUserGroupForm;
        $this->addClientGroupForm = $addClientGroupForm;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            var_dump($data);die;
            $client = new Client();
            $client->setName($data['name']);
            $client->setClientIdentifier(md5($data['name']));
            $client->setClientSecret(md5($data['name'] . (new \DateTime())->format('dmy')));
            $client->setClientType(intval($data['type']));

            foreach ($data['users'] as $userId) {
                $user = $this->entityManager->getRepository(User::class)->find($userId);
                $client->addUser($user);
            }

            $client->setDateAdd(new \DateTime());
            $client->setRedirectUri('http://fake/');

            $this->entityManager->persist($client);
            $this->entityManager->flush();
        }

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService,
            'title' => 'Dodaj aplikację'
        ]);
    }

    /**
     * @return ViewModel
     */
    public function showAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $id = $this->params()->fromRoute('id');
        $client = $this->entityManager->getRepository(Client::class)->find($id);
        $groups = $client->getGroups();
        $this->changeClientUserGroupForm->setAttribute('action', '/apps/changeUserGroup/' . $id);
        $this->addClientGroupForm->setAttribute('action', '/apps/addClientGroup/' . $id);
        $data = [
            'name' => $client->getName(),
            'type' => $client->getClientType(),
            'client_identifier' => $client->getClientIdentifier(),
            'client_secret' => $client->getClientSecret(),
            'redirect_uri' => $client->getRedirectUri(),
        ];

        foreach ($client->getUsers() as $user) {
            $data['users'][] = $user->getId();
        }

        $this->addClientAppForm->setData($data);

        foreach ($this->addClientAppForm->getElements() as $element) {
            $element->setAttribute('readonly', true);
        }

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService,
            'id' => $id,
            'title' => $client->getName(),
            'users' => $client->getUsers(),
            'clientUserGroupForm' => $this->changeClientUserGroupForm,
            'groups' => $groups,
            'addClientGroupForm' => $this->addClientGroupForm,
        ]);
    }

    /**
     * @return ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $id = $this->params()->fromRoute('id');
        $client = $this->entityManager->getRepository(Client::class)->find($id);
        $groups = $client->getGroups();
        $data = [
            'name' => $client->getName(),
            'type' => Client::CLIENT_TYPES[$client->getClientType()],
            'client_identifier' => $client->getClientIdentifier(),
            'client_secret' => $client->getClientSecret(),
            'redirect_uri' => $client->getRedirectUri(),
        ];

        foreach ($client->getUsers() as $user) {
            $data['users'][] = $user->getId();
        }

        $this->addClientAppForm->setData($data);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            $client->setName($data['name']);
            $client->setClientType($data['type']);
            $client->setRedirectUri($data['redirect_uri']);
            $this->entityManager->getRepository(Client::class)->removeRelation($id);

            foreach ($data['users'] as $userId) {
                $user = $this->entityManager->getRepository(User::class)->find($userId);
                $client->addUser($user);
            }

            $client->setDateEdit(new \DateTime());

            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $this->redirect()->toRoute('apps', ['action' => 'show', 'id' => $id]);
        }

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService,
            'id' => $id,
            'title' => 'Edytuj ' . $client->getName(),
            'users' => $client->getUsers(),
            'clientUserGroupForm' => $this->changeClientUserGroupForm,
            'groups' => $groups,
            'addClientGroupForm' => $this->addClientGroupForm,
        ]);
    }

    /**
     *
     */
    public function changeUserGroupAction() {
        $id = $this->params()->fromRoute('id');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            var_dump($data);die;
            $user = $this->entityManager->getRepository(User::class)->find(intval($data['user']));
            $group = $this->entityManager->getRepository(ClientGroups::class)->find(intval($data['group']));

            $group->addUser($user);


            if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
                return $this->redirect()->toRoute('apps', ['action' => 'show', 'id' => $id]);
            } else {
                return $this->redirect()->toRoute('apps', ['action' => 'edit', 'id' => $id]);
            }
        }
    }

    /**
     *
     */
    public function addClientGroupAction() {
        $id = $this->params()->fromRoute('id');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            $clientGroup = new ClientGroups();
            $clientGroup->setName($data['name']);
            $clientGroup->setDateAdd(new \DateTime());
            $clientGroup->setDateEdit(new \DateTime());
            $clientGroup->addClient($client);

            $this->entityManager->persist($clientGroup);
            $this->entityManager->flush();

            if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
                return $this->redirect()->toRoute('apps', ['action' => 'show', 'id' => $id]);
            } else {
                return $this->redirect()->toRoute('apps', ['action' => 'edit', 'id' => $id]);
            }
        }

        if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
            return $this->redirect()->toRoute('apps', ['action' => 'show', 'id' => $id]);
        } else {
            return $this->redirect()->toRoute('apps', ['action' => 'edit', 'id' => $id]);
        }
    }
}