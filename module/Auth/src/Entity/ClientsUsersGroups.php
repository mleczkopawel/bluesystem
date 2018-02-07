<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 26.01.18
 * Time: 20:47
 */

namespace Auth\Entity;

use Auth\Traits\ArrayOperations;
use Auth\Traits\Main;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class ClientsUsersGroups
 * @package Auth\Entity
 *
 * @ORM\Table(name="clients_users_groups")
 * @ORM\Entity
 */
class ClientsUsersGroups {

    use Main;
    use ArrayOperations;

    /**
     * @ORM\ManyToOne(targetEntity="Auth\Entity\Client", inversedBy="clientsUsersGroups")
     * @ORM\JoinColumn(name="client", referencedColumnName="id", nullable=true)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Auth\Entity\User", inversedBy="clientsUsersGroups")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Auth\Entity\Group", inversedBy="clientsUsersGroups")
     * @ORM\JoinColumn(name="db_group", referencedColumnName="id", nullable=true)
     */
    private $group;

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     * @return ClientsUsersGroups
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return ClientsUsersGroups
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     * @return ClientsUsersGroups
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }
}