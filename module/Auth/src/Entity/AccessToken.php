<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 21:31
 */

namespace Auth\Entity;

use Auth\Traits\AuthCodes;
use Auth\Traits\Main;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class AccessToken
 * @package Auth\Entity
 *
 * @ORM\Table(name="access_token")
 * @ORM\Entity(repositoryClass="Auth\Repository\AccessTokenRepository")
 */
class AccessToken {

    use Main;
    use AuthCodes;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=40, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=50, nullable=true)
     */
    private $scope;

    /**
     * @ORM\ManyToOne(targetEntity="Auth\Entity\Client")
     * @ORM\JoinColumn(name="client", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Auth\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return AccessToken
     */
    public function setToken(string $token): AccessToken
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * @param \DateTime $expires
     * @return AccessToken
     */
    public function setExpires(\DateTime $expires): AccessToken
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     * @return AccessToken
     */
    public function setScope(string $scope): AccessToken
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     * @return AccessToken
     */
    public function setClient(Client $client): AccessToken
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return AccessToken
     */
    public function setUser(User $user): AccessToken
    {
        $this->user = $user;
        return $this;
    }

    public function toArray()
    {
        return [
            'token' => $this->token,
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'expires' => $this->expires,
            'scope' => $this->scope,
        ];
    }
}