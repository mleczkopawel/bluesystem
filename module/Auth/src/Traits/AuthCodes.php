<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 06.01.18
 * Time: 00:34
 */

namespace Auth\Traits;


trait AuthCodes {

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", nullable=false)
     */
    private $client_id;

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return AuthCodes
     */
    public function setUserId(int $user_id): AuthCodes
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     * @return AuthCodes
     */
    public function setClientId(int $client_id): AuthCodes
    {
        $this->client_id = $client_id;
        return $this;
    }

    public static function fromArray($params)
    {
        $token = new self();
        foreach ($params as $property => $value) {
            $token->$property = $value;
        }
        return $token;
    }

}