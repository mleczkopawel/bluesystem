<?php

namespace Mail\Controller;

use Doctrine\ORM\EntityManager;
use Mail\Service\Mailer;
use Mail\Entity\Mail;
use Mail\Service\MailManager;
use Zend\Mvc\Controller\AbstractActionController;

class CronController extends AbstractActionController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MailManager
     */
    protected $mailManager;

    /**
     * @var Mailer
     */
    protected $mailer;

    public function __construct (EntityManager $entityManager, Mailer $mailer, MailManager $mailManager)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->mailManager = $mailManager;
    }

    public function indexAction()
    {
        $mails = $this->entityManager->getRepository(Mail::class)->findBy(['sendDate' => null], ['creationDate' => 'ASC'], 5);

        /* @var $mail Mail */
        foreach($mails as $mail) {
            if($mail->getCreationDate() > new \DateTime()) {
                break;
            }

            $error = $this->mailer->send($mail);

            if($error) {
                $mail->setError($error);
            }

            $mail->setSendDate(new \DateTime());
            $this->mailManager->update($mail);
        }

        return $this->getResponse()->setContent('OK');
    }
}
