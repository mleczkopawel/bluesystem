<?php
namespace Mail\Service;

use Mail\Entity\Mail;
use Doctrine\ORM\EntityManager;

class MailManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Mail $mail)
    {
        $this->update($mail);
    }

    public function createFromTitle($title, array $params = [])
    {
        $mail = new Mail();
        $mail->setTitle($title);
        $mail->setParams($params);
    }

    public function update(Mail $mail)
    {
        $this->entityManager->persist($mail);
        $this->entityManager->flush();

        return $mail;
    }
}