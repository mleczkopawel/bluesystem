<?php

namespace Log\Entity;

use Auth\Entity\Client;
use Auth\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Log\Repository\MainLogRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discrimer", type="string")
 * @ORM\Table(name="logs")
 * @ORM\DiscriminatorMap({"system"="SystemLog","apiapiapi1"="ApiApiApi1Log","apiapiapi2"="ApiApiApi2Log"})
 */
abstract class MainLog
{

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name = null;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\Client", mappedBy="logs")
     */
    private $clients = null;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\User", mappedBy="logs")
     */
    private $users = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false, unique=true);
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edit", type="datetime", nullable=true)
     */
    private $dateEdit = null;

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = get_object_vars($this);

        return $data;
    }

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MainLog
     */
    public function setName(string $name) : \Log\Entity\MainLog
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     * @return MainLog
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     * @return MainLog
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @param Client $client
     */
    public function addClient(\Auth\Entity\Client $client)
    {
        $this->clients[] = $client;
    }

    /**
     * @param User $user
     */
    public function addUser(\Auth\Entity\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * @param int $id
     * @return Main
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * @param \DateTime $dateAdd
     * @return Main
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }

    /**
     * @param \DateTime $dateEdit
     * @return Main
     */
    public function setDateEdit($dateEdit)
    {
        $this->dateEdit = $dateEdit;
        return $this;
    }


}

