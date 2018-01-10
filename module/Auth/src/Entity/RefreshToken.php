<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 23:17
 */

namespace Auth\Entity;

use Auth\Traits\ArrayOperations;
use Auth\Traits\AuthCodes;
use Auth\Traits\Main;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class RefreshToken
 * @package Auth\Entity
 *
 * @ORM\Table(name="refresh_token")
 * @ORM\Entity(repositoryClass="Auth\Repository\RefreshTokenRepository")
 */
class RefreshToken {

    use Main;
    use AuthCodes;

    /**
     * @var string
     *
     * @ORM\Column(name="refresh_token", type="string", length=40, unique=true, nullable=false)
     */
    private $refreshToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires", type="datetime")
     */
    private $expires;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=40, nullable=true)
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
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return RefreshToken
     */
    public function setRefreshToken(string $refreshToken): RefreshToken
    {
        $this->refreshToken = $refreshToken;
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
     * @return RefreshToken
     */
    public function setExpires(\DateTime $expires): RefreshToken
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
     * @return RefreshToken
     */
    public function setScope(string $scope): RefreshToken
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return RefreshToken
     */
    public function setClient(Client $client): RefreshToken
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return RefreshToken
     */
    public function setUser(User $user): RefreshToken
    {
        $this->user = $user;
        return $this;
    }

    public function toArray()
    {
        return [
            'refresh_token' => $this->refresh_token,
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'expires' => $this->expires,
            'scope' => $this->scope,
        ];
    }
}