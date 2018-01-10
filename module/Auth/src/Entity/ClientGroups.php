<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 08.01.18
 * Time: 11:15
 */

namespace Auth\Entity;

use Auth\Traits\ArrayOperations;
use Auth\Traits\Main;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class ClientGroups
 * @package Auth\Entity
 *
 * @ORM\Table(name="client_group")
 * @ORM\Entity(repositoryClass="Auth\Repository\ClientGroupRepository")
 */
class ClientGroups {

    use Main;
    use ArrayOperations;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\Client", mappedBy="groups")
     */
    private $clients;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\User", mappedBy="users")
     */
    private $users;


    /**
     * ClientGroups constructor.
     */
    public function __construct() {
        $this->clients = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ClientGroups
     */
    public function setName(string $name): ClientGroups
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
     * @return ClientGroups
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
        return $this;
    }

    /**
     * @param Client $client
     * @return Client
     */
    public function addClient(Client $client) {
        $client->addGroup($this);
        $this->clients[] = $client;
        return $client;
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
     * @return ClientGroups
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(User $user) {
        $user->addClientUserGroup($this);
        $this->users[] = $user;
        return $this;
    }
}