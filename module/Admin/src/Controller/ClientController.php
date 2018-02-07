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
use Auth\Entity\ClientsUsersGroups;
use Auth\Entity\Group;
use Auth\Entity\User;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class ClientsController
 * @package Admin\Controller
 */
class ClientController extends AbstractActionController {

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
     * @var array
     */
    private $config;

    /**
     * ClientsController constructor.
     * @param EntityManager $entityManager
     * @param AddClientAppForm $addClientApp
     * @param FlashMessengerService $flashMessengerService
     * @param ChangeClientUserGroupForm $changeClientUserGroupForm
     * @param AddClientGroupForm $addClientGroupForm
     * @param array $config
     */
    public function __construct(EntityManager $entityManager,
                                AddClientAppForm $addClientApp,
                                FlashMessengerService $flashMessengerService,
                                ChangeClientUserGroupForm $changeClientUserGroupForm,
                                AddClientGroupForm $addClientGroupForm,
                                array $config)
    {
        $this->entityManager = $entityManager;
        $this->addClientAppForm = $addClientApp;
        $this->flashMessengerService = $flashMessengerService;
        $this->changeClientUserGroupForm = $changeClientUserGroupForm;
        $this->addClientGroupForm = $addClientGroupForm;
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

    public function listAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $clients = $this->entityManager->getRepository(Client::class)->findAll();

        return new ViewModel([
            'messages' => $this->flashMessengerService->getMessages(),
            'clients' => $clients,
        ]);
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
            $client = new Client();
            $client->setName($data['name']);
            $client->setClientIdentifier(md5($data['name']));
            $client->setClientSecret(md5($data['name'] . (new \DateTime())->format('dmy')));
            $client->setClientType(intval($data['type']));
            $client->setRedirectUri($data['redirect_uri']);


            foreach ($data['users'] as $userId) {
                $user = $this->entityManager->getRepository(User::class)->find($userId);
                /**
                 * @var ClientsUsersGroups $clientsUserGroups
                 */
                $clientsUserGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['client' => $client, 'user' => $user]);
                if (!$clientsUserGroups) {
                    $clientsUserGroups = new ClientsUsersGroups();
                    $clientsUserGroups->setDateAdd(new \DateTime());
                    $clientsUserGroups->setClient($client);
                }
                if (!$clientsUserGroups->getGroup()) {
                    $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => 'Gość']);
                    if (!$group) {
                        $group = new Group();
                        $group->setDateAdd(new \DateTime());
                        $group->setName('Gość');

                        $this->entityManager->persist($group);
                    }
                    $clientsUserGroups->setGroup($group);
                }
                $clientsUserGroups->setUser($user);
                $this->entityManager->persist($clientsUserGroups);
            }

            $superUser = $this->entityManager->getRepository(User::class)->find(1);
            $adminGroup = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => 'Administrator']);
            if (!$adminGroup) {
                $adminGroup = new Group();
                $adminGroup->setName('Administrator');
                $adminGroup->setDateAdd(new \DateTime());

                $this->entityManager->persist($adminGroup);
            }
            $adminClientsUserGroups = new ClientsUsersGroups();
            $adminClientsUserGroups->setUser($superUser);
            $adminClientsUserGroups->setGroup($adminGroup);
            $adminClientsUserGroups->setClient($client);
            $adminClientsUserGroups->setDateAdd(new \DateTime());
            $this->entityManager->persist($adminClientsUserGroups);

            $client->setDateAdd(new \DateTime());
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $this->flashMessenger()->addMessage('Dodano aplikację <strong>' . $client->getName() . '</strong>', FlashMessenger::NAMESPACE_SUCCESS);
            return $this->redirect()->toRoute('admin/apps', ['action' => 'list']);
        }

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService->getMessages(),
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
        $clientsUsersGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['client' => $client]);
        $this->changeClientUserGroupForm->setAttribute('action', '/admin/apps/changeUserGroup/' . $id);
        $this->addClientGroupForm->setAttribute('action', '/admin/apps/addClientGroup/' . $id);
        $data = [
            'name' => $client->getName(),
            'type' => $client->getClientType(),
            'client_identifier' => $client->getClientIdentifier(),
            'client_secret' => $client->getClientSecret(),
            'redirect_uri' => $client->getRedirectUri(),
        ];

        $valueOptions = [];
        $users = [];
        $groups = [];
        /**
         * @var ClientsUsersGroups $clientsUsersGroup
         */
        foreach ($clientsUsersGroups as $clientsUsersGroup) {
            $group = $clientsUsersGroup->getGroup();
            $user = $clientsUsersGroup->getUser();
            if ($user && $user->getId() != 1) {
                $users[] = $user;
                $data['users'][] = $user->getId();
            } else {}
            if (!$this->isInArray($groups, $group))
                $groups[] = $group;
            $valueOptions[$group->getId()] = $group->getName();
        }

        $this->addClientAppForm->setData($data);

        foreach ($this->addClientAppForm->getElements() as $element) {
            $element->setAttribute('readonly', true);
        }

        $this->changeClientUserGroupForm->get(ChangeClientUserGroupForm::GROUP)->setValueOptions($valueOptions);

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService->getMessages(),
            'id' => $id,
            'title' => $client->getName(),
            'users' => $users,
            'clientUserGroupForm' => $this->changeClientUserGroupForm,
            'addClientGroupForm' => $this->addClientGroupForm,
            'groups' => $groups
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
        $clientsUsersGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['client' => $client]);
        $this->changeClientUserGroupForm->setAttribute('action', '/admin/apps/changeUserGroup/' . $id);
        $this->addClientGroupForm->setAttribute('action', '/admin/apps/addClientGroup/' . $id);
        $data = [
            'name' => $client->getName(),
            'type' => $client->getClientType(),
            'client_identifier' => $client->getClientIdentifier(),
            'client_secret' => $client->getClientSecret(),
            'redirect_uri' => $client->getRedirectUri(),
        ];

        $valueOptions = [];
        $users = [];
        $groups = [];
        /**
         * @var ClientsUsersGroups $clientsUsersGroup
         */
        foreach ($clientsUsersGroups as $clientsUsersGroup) {
            $group = $clientsUsersGroup->getGroup();
            $user = $clientsUsersGroup->getUser();
            if ($user && $user->getId() != 1) {
                $users[] = $user;
                $data['users'][] = $user->getId();
            } else {}
            if (!$this->isInArray($groups, $group))
                $groups[] = $group;
            $valueOptions[$group->getId()] = $group->getName();
        }
        $this->addClientAppForm->setData($data);
        $this->changeClientUserGroupForm->get(ChangeClientUserGroupForm::GROUP)->setValueOptions($valueOptions);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $client->setName($data['name']);
            $client->setClientType(intval($data['type']));
            $client->setRedirectUri($data['redirect_uri']);

            $usersEm = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['client' => $client]);
            $usersEmArr = [];

            foreach ($usersEm as $item)
                if ($item->getUser())
                    if ($item->getUser()->getLogin() != $this->config['login'])
                        $usersEmArr[] = $item->getUser()->getId();

            foreach ($data['users'] as $userId) {
                $user = $this->entityManager->getRepository(User::class)->find($userId);
                /**
                 * @var ClientsUsersGroups $clientsUserGroups
                 */
                $clientsUserGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['client' => $client, 'user' => $user]);
                if (!$clientsUserGroups) {
                    $clientsUserGroups = new ClientsUsersGroups();
                    $clientsUserGroups->setDateAdd(new \DateTime());
                    $clientsUserGroups->setClient($client);
                }
                if (!$clientsUserGroups->getGroup()) {
                    $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => 'Gość']);
                    if (!$group) {
                        $group = new Group();
                        $group->setDateAdd(new \DateTime());
                        $group->setName('Gość');

                        $this->entityManager->persist($group);
                    }
                    $clientsUserGroups->setGroup($group);
                }
                $clientsUserGroups->setUser($user);
                $this->entityManager->persist($clientsUserGroups);
            }

            $diff2 = array_diff($usersEmArr, $data['users']);
            foreach ($diff2 as $item) {
                $user = $this->entityManager->getRepository(User::class)->find($item);
                $clientsUsersGroup = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['client' => $client, 'user' => $user]);
                $this->entityManager->remove($clientsUsersGroup);
                $this->entityManager->flush();
            }

            $client->setDateAdd(new \DateTime());
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            $this->flashMessenger()->addMessage('Pomyślnie zakończono edycję aplikacji <strong>' . $client->getName() . '</strong>', FlashMessenger::NAMESPACE_SUCCESS);
            return $this->redirect()->toRoute('admin/apps', ['action' => 'list']);
        }

        return new ViewModel([
            'form' => $this->addClientAppForm,
            'messages' => $this->flashMessengerService->getMessages(),
            'id' => $id,
            'title' => 'Edytuj ' . $client->getName(),
            'users' => $users,
            'clientUserGroupForm' => $this->changeClientUserGroupForm,
            'addClientGroupForm' => $this->addClientGroupForm,
            'groups' => $groups
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
            $client = $this->entityManager->getRepository(Client::class)->find($id);
            $user = $this->entityManager->getRepository(User::class)->find($data['user']);
            $group = $this->entityManager->getRepository(Group::class)->find($data['group']);
            /**
             * @var ClientsUsersGroups $clientUserGroup
             */
            $clientUserGroup = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['client' => $client, 'user' => $user]);
            $clientUserGroup->setGroup($group);

            $this->entityManager->persist($clientUserGroup);
            $this->entityManager->flush();

            $this->flashMessenger()->addMessage('Przypisano użytkownika <strong>' . $user->getLogin() . '</strong> do grupy <strong>' . $group->getName() . '</strong> w aplikacji <strong>' . $client->getName() . '</strong>', FlashMessenger::NAMESPACE_SUCCESS);
            if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
                return $this->redirect()->toRoute('admin/apps', ['action' => 'show', 'id' => $id]);
            } else {
                return $this->redirect()->toRoute('admin/apps', ['action' => 'edit', 'id' => $id]);
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
            $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => $data['name']]);
            if (!$group) {
                $group = new Group();
                $group->setDateAdd(new \DateTime());
                $group->setName($data['name']);
            }
            $group->setDateEdit(new \DateTime());
            $this->entityManager->persist($group);

            $clientUserGroup = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['client' => $client, 'group' => $group]);
            if (!$clientUserGroup) {
                $clientUserGroup = new ClientsUsersGroups();
                $clientUserGroup->setDateAdd(new \DateTime());
            }
            $clientUserGroup->setDateEdit(new \DateTime());
            $clientUserGroup->setClient($client);
            $clientUserGroup->setGroup($group);

            $this->entityManager->persist($clientUserGroup);

            $this->entityManager->flush();

            $this->flashMessenger()->addMessage('Dodano grupę <strong>' . $group->getName() .'</strong>', FlashMessenger::NAMESPACE_SUCCESS);
            if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
                return $this->redirect()->toRoute('admin/apps', ['action' => 'show', 'id' => $id]);
            } else {
                return $this->redirect()->toRoute('admin/apps', ['action' => 'edit', 'id' => $id]);
            }
        }

        if (explode('/', $request->getHeader('Referer')->getUri())[4] == 'show') {
            return $this->redirect()->toRoute('admin/apps', ['action' => 'show', 'id' => $id]);
        } else {
            return $this->redirect()->toRoute('admin/apps', ['action' => 'edit', 'id' => $id]);
        }
    }

    public function removeAction() {
        $id = $this->params()->fromRoute('id');
        $client = $this->entityManager->getRepository(Client::class)->find($id);

        if ($client) {
            $clientUsersGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['client' => $client]);
            foreach ($clientUsersGroups as $clientUsersGroup) {
                $this->entityManager->remove($clientUsersGroup);
            }

            $this->entityManager->remove($client);
            $this->entityManager->flush();

            $this->flashMessenger()->addMessage('Usunięto aplikację <strong>' . $client->getName() . '</strong>', FlashMessenger::NAMESPACE_ERROR);
        } else {
            $this->flashMessenger()->addMessage('Aplikacja nie istnieje', FlashMessenger::NAMESPACE_ERROR);
        }
        return $this->redirect()->toRoute('admin/apps', ['action' => 'list']);
    }

    private function isInArray(array $array, $object): bool {
        foreach ($array as $item)
            if ($object->getId() == $item->getId())
                return true;

        return false;
    }
}