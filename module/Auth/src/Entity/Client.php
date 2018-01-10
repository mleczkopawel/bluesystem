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
 * Class Client
 * @package Auth\Entity
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="Auth\Repository\ClientRepository")
 */
class Client extends EncryptableField {

    use Main;
    use ArrayOperations;

    /**
     *
     */
    const CLIENT_TYPES = [
        0 => 'web',
        1 => 'android',
        2 => 'ios',
    ];

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="client_identifier", type="string", length=50, nullable=false)
     */
    private $clientIdentifier;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=250, nullable=false)
     */
    private $clientSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", length=252, nullable=false)
     */
    private $redirectUri = '';

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\User", inversedBy="clients")
     * @ORM\JoinTable(name="clients_users")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Auth\Entity\ClientGroups", inversedBy="clients")
     * @ORM\JoinTable(name="clients_groups")
     */
    private $groups;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_type", type="integer", nullable=true)
     */
    private $clientType;

    /**
     * Client constructor.
     */
    public function __construct() {
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }

    /**
     * @param string $clientIdentifier
     * @return Client
     */
    public function setClientIdentifier(string $clientIdentifier): Client
    {
        $this->clientIdentifier = $clientIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     * @return Client
     */
    public function setClientSecret(string $clientSecret): Client
    {
        $this->clientSecret = $this->encryptField($clientSecret);
        return $this;
    }

    /**
     * @param string $clientSecret
     * @return bool
     */
    public function verifyClientSecret(string $clientSecret):bool {
        return $this->verifyEncryptableFieldValue($this->getClientSecret(), $clientSecret);
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     * @return Client
     */
    public function setRedirectUri(string $redirectUri): Client
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers(): PersistentCollection
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     * @return Client
     */
    public function setUsers(array $users): Client
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @param User $user
     * @return Client
     */
    public function addUser(User $user): Client {
        $this->users[] = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getClientType(): int
    {
        return $this->clientType;
    }

    /**
     * @return string
     */
    public function getClientTypeName(): string {
        return self::CLIENT_TYPES[$this->clientType];
    }

    /**
     * @param int $clientType
     * @return Client
     */
    public function setClientType(int $clientType): Client
    {
        $this->clientType = $clientType;
        return $this;
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
     * @return Client
     */
    public function setName(string $name): Client
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     * @return Client
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @param $group
     * @return $this
     */
    public function addGroup($group) {
        $this->groups[] = $group;
        return $this;
    }
}