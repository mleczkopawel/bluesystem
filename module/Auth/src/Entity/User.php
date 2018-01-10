<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 21:22
 */

namespace Auth\Entity;

use Auth\Traits\ArrayOperations;
use Auth\Traits\Main;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;


/**
 * Class User
 * @package Auth\Entity
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Auth\Repository\UserRepository")
 */
class User extends EncryptableField {

    /**
     *
     */
    const STATUS_ACTIVE = 1;

    /**
     *
     */
    const STATUS_RETIRED = 2;

    /**
     *
     */
    const GROUP_ADMIN = 1;

    /**
     *
     */
    const GROUP_MOD = 2;

    /**
     *
     */
    const GROUP_UNASSIGNED = 3;

    use Main;
    use ArrayOperations;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_login", type="datetime", nullable=true)
     */
    private $dateLogin;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_group", type="integer")
     */
    private $group;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\Client", mappedBy="users")
     */
    private $clients;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\ClientGroups", inversedBy="users")
     * @ORM\JoinTable(name="clients_users_groups")
     */
    private $clientUserGroup;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct(array $data = []) {
        $this->login = isset($data['login']) ? $data['login'] : null;
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->status = isset($data['status']) ? $data['status'] : self::STATUS_RETIRED;
        $this->dateAdd = isset($data['dateAdd']) ? $data['dateAdd'] : new \DateTime();
        $this->password = isset($data['password']) ? $data['password'] : null;
        $this->group = isset($data['group']) ? $data['group'] : self::GROUP_UNASSIGNED;
        $this->clients = new ArrayCollection();
        $this->clientUserGroup = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateLogin()
    {
        return $this->dateLogin;
    }

    /**
     * @param \DateTime $dateLogin
     * @return User
     */
    public function setDateLogin(\DateTime $dateLogin): User
    {
        $this->dateLogin = $dateLogin;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $this->encryptField($password);
        return $this;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password):bool {
        return $this->verifyEncryptableFieldValue($this->getPassword(), $password);
    }

    /**
     * @return mixed
     */
    public function getClients() {
        return $this->clients;
    }

    /**
     * @param mixed $clients
     * @return User
     */
    public function setClients(array $clients): User {
        $this->clients = $clients;
        return $this;
    }

    /**
     * @param Client $client
     * @return User
     */
    public function addClient(Client $client): User {
        $client->addUser($this);
        $this->clients[] = $client;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return User
     */
    public function setStatus(int $status): User
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getGroup(): int
    {
        return $this->group;
    }

    /**
     * @param int $group
     * @return User
     */
    public function setGroup(int $group): User
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return array
     */
    public static function getStatusList(): array {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired',
        ];
    }

    /**
     * @return string
     */
    public function getStatusName(): string {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    /**
     * @return array
     */
    public static function getGroupList(): array {
        return [
            self::GROUP_ADMIN => 'Admin',
            self::GROUP_MOD => 'Moderator',
            self::GROUP_UNASSIGNED => 'Nieprzypisany',
        ];
    }

    /**
     * @return string
     */
    public function getGroupName(): string {
        $list = self::getGroupList();
        if (isset($list[$this->group]))
            return $list[$this->group];

        return 'Unknown';
    }

    /**
     * @return string
     */
    public function getViewName(): string {
        return $this->email . '-' . $this->login;
    }

    /**
     * @return array
     */
    public function getHashOptions(): array
    {
        return $this->hashOptions;
    }

    /**
     * @param array $hashOptions
     * @return User
     */
    public function setHashOptions(array $hashOptions): User
    {
        $this->hashOptions = $hashOptions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientUserGroup()
    {
        return $this->clientUserGroup;
    }

    /**
     * @param mixed $clientUserGroup
     * @return User
     */
    public function setClientUserGroup($clientUserGroup)
    {
        $this->clientUserGroup = $clientUserGroup;
        return $this;
    }

    /**
     * @param $clientUserGroup
     * @return $this
     */
    public function addClientUserGroup($clientUserGroup) {
        $this->clientUserGroup[] = $clientUserGroup;
        return $this;
    }
}