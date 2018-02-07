<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 26.01.18
 * Time: 20:44
 */

namespace Auth\Entity;

use Auth\Traits\ArrayOperations;
use Auth\Traits\Main;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Group
 * @package Auth\Entity
 *
 * @ORM\Table(name="db_group")
 * @ORM\Entity
 */
class Group {

    use Main;
    use ArrayOperations;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Auth\Entity\ClientsUsersGroups", mappedBy="group")
     */
    private $clientsUsersGroups;

    /**
     * Group constructor.
     */
    public function __construct() {
        $this->clientsUsersGroups = new ArrayCollection();
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
     * @return Group
     */
    public function setName(string $name): Group
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientsUsersGroups()
    {
        return $this->clientsUsersGroups;
    }

    /**
     * @param mixed $clientsUsersGroups
     * @return Group
     */
    public function setClientsUsersGroups($clientsUsersGroups)
    {
        $this->clientsUsersGroups = $clientsUsersGroups;
        return $this;
    }
}