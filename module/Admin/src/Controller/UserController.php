<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 27.01.18
 * Time: 19:36
 */

namespace Admin\Controller;


use Admin\Form\AddUserForm;
use Admin\Form\UserGroupForm;
use Auth\Entity\Client;
use Auth\Entity\ClientsUsersGroups;
use Auth\Entity\Group;
use Auth\Entity\User;
use Auth\Service\FlashMessengerService;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element\Radio;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package Admin\Controller
 */
class UserController extends AbstractActionController {
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var AddUserForm
     */
    private $addUserForm;
    /**
     * @var FlashMessengerService
     */
    private $flashMessengerService;
    /**
     * @var UserGroupForm
     */
    private $userGroupForm;

    /**
     * UserController constructor.
     * @param EntityManager $entityManager
     * @param FlashMessengerService $flashMessengerService
     * @param AddUserForm $addUserForm
     * @param UserGroupForm $userGroupForm
     */
    public function __construct(EntityManager $entityManager,
                                FlashMessengerService $flashMessengerService,
                                AddUserForm $addUserForm,
                                UserGroupForm $userGroupForm)
    {
        $this->entityManager = $entityManager;
        $this->addUserForm = $addUserForm;
        $this->flashMessengerService = $flashMessengerService;
        $this->userGroupForm = $userGroupForm;
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
    public function listAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new ViewModel([
            'users' => $users,
            'messages' => $this->flashMessengerService->getMessages(),
        ]);
    }

    /**
     * @return ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $id = $this->params()->fromRoute('id');
        $request = $this->getRequest();
        $count = 0;

        if ($id) {
            $user = $this->entityManager->getRepository(User::class)->find($id);

            $clientsUsersGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findAll();
            $clientsGroups = [];
            foreach ($clientsUsersGroups as $clientsUsersGroup) {
                $clientsGroups[] = [
                    'client' => $clientsUsersGroup->getClient()->getId(),
                    'group' => $clientsUsersGroup->getGroup()->getId(),
                ];
            }
            $clientsGroups = $this->superUnique($clientsGroups);
            $clientGroups = [];
            foreach ($clientsGroups as $group) {
                $clientGroups[$group['client']]['id'] = $group['client'];
                $clientGroups[$group['client']]['groups'][] = $group['group'];
            }

            $clientsGroups = [];
            foreach ($clientGroups as $group) {
                $clientsGroups[] = $group;
            }
            $count = count($clientsGroups);

            for ($i = 0; $i < $count; $i++) {
                $client = $this->entityManager->getRepository(Client::class)->find($clientsGroups[$i]['id']);
                $this->userGroupForm->add([
                    'name' => UserGroupForm::GROUP . '-' . $i,
                    'type' => Radio::class,
                    'options' => [
                        'label' => $client->getName(),
                    ],
                ]);
                $groups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['client' => $client]);
                $valueOptions = [];
                foreach ($groups as $group) {
                    $valueOptions[$group->getGroup()->getId() . '-' . $client->getId()] = ' ' . $group->getGroup()->getName();
                }
                $this->userGroupForm->get(UserGroupForm::GROUP . '-' . $i)->setValueOptions($valueOptions);
            }
            $this->userGroupForm->setAttribute('action', '/admin/user/change-group/' . $id);

            $data = [
                AddUserForm::NAME => $user->getLogin(),
                AddUserForm::EMAIL => $user->getEmail(),
                AddUserForm::GROUP => $user->getGroup(),
                AddUserForm::PASSWORD => $user->getPassword(),
            ];
            $this->addUserForm->setData($data);

            $data2 = [];
            $userClientGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['user' => $user]);
            foreach ($userClientGroups as $userClientGroup) {
                for ($i = 0; $i < $count; $i++) {
                    if ($userClientGroup->getClient()->getName() == $this->userGroupForm->get(UserGroupForm::GROUP . '-' . $i)->getLabel()) {
                        $data2[UserGroupForm::GROUP . '-' . $i] = $userClientGroup->getGroup()->getId() . '-' . $userClientGroup->getClient()->getId();
                    }
                }
            }
            $this->userGroupForm->setData($data2);
        } else {
            $this->userGroupForm = null;
        }

        if ($request->isPost()) {
            $data = $request->getPost();
//            TODO: VALIDATOR
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['login' => $data[AddUserForm::NAME], 'email' => $data[AddUserForm::EMAIL]]);
            if (!$user) {
                $user = new User();
                $user->setDateAdd(new \DateTime());
                $this->flashMessenger()->addMessage('Dodano nowego użytkownika', FlashMessenger::NAMESPACE_SUCCESS);
            } else {
                $user->setDateEdit(new \DateTime());
                $this->flashMessenger()->addMessage('Zmieniono dane użytkownikowi', FlashMessenger::NAMESPACE_SUCCESS);
            }
            $user->setLogin($data[AddUserForm::NAME])
                ->setEmail($data[AddUserForm::EMAIL])
                ->setStatus(1)
                ->setGroup($data[AddUserForm::GROUP])
                ->setPassword($data[AddUserForm::PASSWORD]);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirect()->toRoute('admin/user', ['action' => 'list']);
        }

        return new ViewModel([
            'form' => $this->addUserForm,
            'userGroupForm' => $this->userGroupForm,
            'messages' => $this->flashMessengerService->getMessages(),
            'countGroups' => $count,
        ]);
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeAction() {
        $id = $this->params()->fromRoute('id');
        $user = $this->entityManager->getRepository(User::class)->find($id);
        $clientsUserGroups = $this->entityManager->getRepository(ClientsUsersGroups::class)->findBy(['user' => $user]);

        /**
         * @var ClientsUsersGroups $clientsUserGroup
         */
        foreach ($clientsUserGroups as $clientsUserGroup) {
            $clientsUserGroup->setUser(null);
            $this->entityManager->persist($clientsUserGroup);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->flashMessenger()->addMessage('Usunięto użytkownika', FlashMessenger::NAMESPACE_SUCCESS);
        return $this->redirect()->toRoute('admin/user', ['action' => 'list']);
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeGroupAction() {
        $id = $this->params()->fromRoute('id');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $user = $this->entityManager->getRepository(User::class)->find($id);
            $data = $request->getPost();
            foreach ($data as $key => $datum) {
                if (explode('-', $key)[0] == 'group') {
                    $dataItem = explode('-', $datum);
                    $client = $this->entityManager->getRepository(Client::class)->find($dataItem[1]);
                    $group = $this->entityManager->getRepository(Group::class)->find($dataItem[0]);
                    $clientUserGroup = $this->entityManager->getRepository(ClientsUsersGroups::class)->findOneBy(['user' => $user, 'client' => $client]);
                    if (!$clientUserGroup) {
                        $clientUserGroup = new ClientsUsersGroups();
                        $clientUserGroup->setDateAdd(new \DateTime())
                            ->setUser($user)
                            ->setClient($client);
                    } else {
                        $clientUserGroup->setDateEdit(new \DateTime());
                    }
                    $clientUserGroup->setGroup($group);

                    $this->entityManager->persist($clientUserGroup);
                    $this->entityManager->flush();
                }
            }
            $this->flashMessenger()->addMessage('Zmieniono grupy użytkownikowi', FlashMessenger::NAMESPACE_SUCCESS);
            return $this->redirect()->toRoute('admin/user', ['action' => 'add', 'id' => $id]);
        }
    }

    /**
     * @param $array
     * @return array
     */
    private function superUnique($array){
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->superUnique($value);
            }
        }

        return $result;
    }
}