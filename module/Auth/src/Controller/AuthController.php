<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 19:31
 */

namespace Auth\Controller;


use Auth\Entity\User;
use Auth\Form\UserLoginForm;
use Auth\Form\UserRegisterForm;
use Auth\Service\AuthManager;
use Auth\Service\FlashMessengerService;
use Auth\Service\UserManager;
use Doctrine\ORM\EntityManager;
use Mail\Entity\Mail;
use Mail\Service\MailManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;

/**
 * Class AuthController
 * @package Auth\Controller
 */
class AuthController extends AbstractActionController {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UserLoginForm
     */
    private $userLoginForm;

    /**
     * @var FlashMessengerService
     */
    private $flashMessengerService;

    /**
     * @var AuthManager
     */
    private $authManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var MailManager
     */
    private $mailManager;

    /**
     * @var UserRegisterForm
     */
    private $userRegisterForm;

    /**
     * AuthController constructor.
     * @param EntityManager $entityManager
     * @param UserLoginForm $userLoginForm
     * @param FlashMessengerService $flashMessengerService
     * @param AuthManager $authManager
     * @param UserManager $userManager
     * @param MailManager $mailManager
     * @param UserRegisterForm $userRegisterForm
     */
    public function __construct(
        EntityManager $entityManager,
        UserLoginForm $userLoginForm,
        FlashMessengerService $flashMessengerService,
        AuthManager $authManager,
        UserManager $userManager,
        MailManager $mailManager,
        UserRegisterForm $userRegisterForm) {
        $this->entityManager = $entityManager;
        $this->userLoginForm = $userLoginForm;
        $this->flashMessengerService = $flashMessengerService;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
        $this->mailManager = $mailManager;
        $this->userRegisterForm = $userRegisterForm;
    }

    /**
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e) {
        $response =  parent::onDispatch($e);
        $this->layout()->setTemplate('auth/layout');
        return $response;
    }

    /**
     * @return ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function loginAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $this->userLoginForm->setData($data);
            if ($this->userLoginForm->isValid()) {
                $result = $this->authManager->login($data['login'], $data['password'], $data['rememberMe'] ? $data['rememberMe'] : false);
                if ($result->isValid()) {
                    $this->flashMessenger()->addMessage('Zalogowano pomyślnie', FlashMessenger::NAMESPACE_SUCCESS);
                    $user = $this->entityManager->getRepository(User::class)->find($this->authManager->getIdentity()['id']);
                    $user->setDateLogin(new \DateTime());
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    return $this->redirect()->toRoute('admin');
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addMessage(AuthManager::MESSAGE_TEMPLATES[$message], FlashMessenger::NAMESPACE_ERROR);
                    }
                    return $this->redirect()->toRoute('auth', ['action' => 'login']);
                }
            } else {
                foreach ($this->userLoginForm->getMessages() as $key => $messages) {
                    foreach ($messages as $message) {
                        $this->flashMessenger()->addMessage($this->userLoginForm->messageTemplates[$key] . ': ' . $message, FlashMessenger::NAMESPACE_ERROR);
                    }
                }
                return $this->redirect()->toRoute('auth', ['action' => 'login']);
            }
        }

        return new ViewModel([
            'form' => $this->userLoginForm,
            'messages' => $this->flashMessengerService->getMessages(),
        ]);
    }

    /**
     * @return ViewModel
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerAction(): ViewModel {
        $this->flashMessengerService->setFlashMessenger($this->flashMessenger());
        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->userRegisterForm->setData($request->getPost());
            if ($this->userRegisterForm->isValid()) {
                $user = $this->userManager->addUser($this->userRegisterForm->getData()->toArray());
                $mail = new Mail();
                $mail->setTitle('Zarejestrowano!')->setParams(['asd' => 'asdff'])->setSender('admin@bros-media.pl')->setRecipients([$user->getEmail(), $user->getLogin()]);
                $mail->setLayout('auth/elayout');
                $mail->setTemplate('email/register');
                $this->mailManager->create($mail);
                $this->flashMessenger()->addMessage('Zarejestrowano pomyślnie', FlashMessenger::NAMESPACE_SUCCESS);
                return $this->redirect()->toRoute('auth', ['action' => 'login']);
            } else {
                foreach ($this->userRegisterForm->getMessages() as $message) {
                    $this->flashMessenger()->addMessage($message, FlashMessenger::NAMESPACE_ERROR);
                }
                return $this->redirect()->toRoute('auth', ['action' => 'register']);
            }
        }

        return new ViewModel([
            'form' => $this->userRegisterForm,
            'messages' => $this->flashMessengerService->getMessages(),
        ]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function logoutAction(): Response {
        $this->authManager->logout();
        return $this->redirect()->toRoute('auth', ['action' => 'login']);
    }

}