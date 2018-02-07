<?php
namespace Auth\Controller;

use Auth\Entity\Client;
use Auth\Entity\ClientsUsersGroups;
use Auth\Entity\Group;
use Auth\Form\UserRegisterForm;
use Auth\Service\OAuthService;
use Auth\Service\UserManager;
use Doctrine\ORM\EntityManager;
use OAuth2\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class OAuthController
 * @package Auth\Controller
 */
class OAuthController extends AbstractActionController {

    /**
     * @var OAuthService
     */
    private $oAuthService;
    /**
     * @var UserRegisterForm
     */
    private $userRegisterForm;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * OAuthController constructor.
     * @param OAuthService $OAuthService
     * @param UserRegisterForm $userRegisterForm
     * @param UserManager $userManager
     * @param EntityManager $entityManager
     */
    public function __construct(OAuthService $OAuthService,
                                UserRegisterForm $userRegisterForm,
                                UserManager $userManager,
                                EntityManager $entityManager) {
        $this->oAuthService = $OAuthService;
        $this->oAuthService->initServer();
        $this->userRegisterForm = $userRegisterForm;
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
    }


    /**
     * @return JsonModel
     */
    public function indexAction(): JsonModel {
        $response = null;
        if ($this->oauth()->auth()) {
            $response = [
                'success' => 'YouGotIt',
            ];
        }

        return new JsonModel($response);
    }


    /**
     * @return void
     */
    public function getTokenAction(): void {
        $this->oAuthService->getServer()->handleTokenRequest(Request::createFromGlobals())->send();die;
    }

    /**
     * @return JsonModel
     */
    public function getUserIdAction(): JsonModel {
        $response = null;
        if ($this->oauth()->auth()) {
            $token = $this->oAuthService->getServer()->getAccessTokenData(Request::createFromGlobals());
            $response = [
                'user_id' => $token['user_id'],
            ];
        }

        return new JsonModel($response);
    }

    /**
     * @return JsonModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerAction(): JsonModel {
        $response = null;
        $data = null;
        if ($this->oauth()->auth()) {
            $dataPost = $this->params()->fromPost();
            $data = [
                UserRegisterForm::LOGIN => $dataPost['login'],
                UserRegisterForm::EMAIL => $dataPost['email'],
                UserRegisterForm::PASSWORD => $dataPost['password'],
                UserRegisterForm::REPASSWORD => $dataPost['repassword'],
            ];
            $this->userRegisterForm->setData($data);
            $this->userRegisterForm->get(UserRegisterForm::CSRF)->setAttribute('required', 'false');
            $this->userRegisterForm->getInputFilter()->get(UserRegisterForm::CSRF)->setRequired(false);
            if ($this->userRegisterForm->isValid()) {
                $token = $this->oAuthService->getServer()->getAccessTokenData(Request::createFromGlobals());
                $user = $this->userManager->addUser($this->userRegisterForm->getData()->toArray());
                $client = $this->entityManager->getRepository(Client::class)->find($token['client_id']);
                $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => 'Gość']);
                if (!$group) {
                    $group = new Group();
                    $group->setName('Gość')
                        ->setDateAdd(new \DateTime());

                    $this->entityManager->persist($group);
                }
                $clientsUsersGroups = new ClientsUsersGroups();
                $clientsUsersGroups->setClient($client)
                    ->setUser($user)
                    ->setGroup($group)
                    ->setDateAdd(new \DateTime());

                $this->entityManager->persist($clientsUsersGroups);
                $this->entityManager->flush();

                $response = [
                    'code' => 1,
                    'message' => 'User ' . $user->getLogin() . ' registered',
                    'response' => [
                        'user' => [
                            'id' => $user->getId(),
                            'login' => $user->getLogin(),
                            'email' => $user->getEmail(),
                        ],
                        'group' => [
                            'id' => $group->getId(),
                            'name' => $group->getName(),
                        ],
                        'client' => [
                            'id' => $client->getId(),
                            'name' => $client->getName(),
                        ],
                    ],
                ];
            } else {
                $errors = [];
                foreach ($this->userRegisterForm->getMessages() as $key => $message) {
                    foreach ($message as $item)
                        $errors[$key][] = $item;
                }
                $response = [
                    'code' => 0,
                    'message' => 'Form not valid',
                    'response' => $errors,
                ];
            }
        }

        return new JsonModel($response);
    }
}
