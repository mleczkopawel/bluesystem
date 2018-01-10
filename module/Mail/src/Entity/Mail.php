<?php

namespace Mail\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Mail\Repository\MailRepository")
 * @ORM\Table(name="mail")
 */
class Mail
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="sender")
     */
    protected $sender;

    /**
     * @ORM\Column(type="json_array", name="recipients")
     */
    protected $recipients;

    /**
     * @ORM\Column(type="string", name="title", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", name="layout")
     */
    protected $layout;

    /**
     * @ORM\Column(type="string", name="template")
     */
    protected $template;

    /**
     * @ORM\Column(type="json_array", name="params", nullable=true)
     */
    protected $params;

    /**
     * @ORM\Column(type="string", name="error", nullable=true)
     */
    protected $error;

    /**
     * @ORM\Column(type="datetime", name="creation_date")
     */
    protected $creationDate;

    /**
     * @ORM\Column(type="datetime", name="send_date", nullable=true)
     */
    protected $sendDate;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->layout = 'layout/email';
        $this->template = 'email/default';
    }

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->sender = !empty($data['sender']) ? $data['sender'] : null;
        $this->recipients = !empty($data['recipients']) ? $data['recipients'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->layout = !empty($data['layout']) ? $data['layout'] : null;
        $this->template = !empty($data['template']) ? $data['template'] : null;
        $this->params = !empty($data['params']) ? $data['params'] : null;
        $this->error = !empty($data['error']) ? $data['error'] : null;
        $this->creationDate = !empty($data['creation_date']) ? new \DateTime($data['creation_date']) : null;
        $this->sendDate = !empty($data['send_date']) ? new \DateTime($data['send_date']) : null;
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'recipients' => $this->recipients,
            'title' => $this->title,
            'layout' => $this->layout,
            'template' => $this->template,
            'params' => $this->params,
            'error' => $this->error,
            'creation_date' => $this->creationDate,
            'send_date' => $this->sendDate
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getSendDate()
    {
        return $this->sendDate;
    }

    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }
}