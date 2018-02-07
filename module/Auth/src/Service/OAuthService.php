<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 22:30
 */

namespace Auth\Service;


use Auth\Entity\AccessToken;
use Auth\Entity\AuthorizationCode;
use Auth\Entity\Client;
use Auth\Entity\RefreshToken;
use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\Request;
use OAuth2\Server;
use OAuth2\GrantType\RefreshToken as OAuthRefreshToken;

/**
 * Class OAuthService
 * @package Auth\Service
 */
class OAuthService {

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $config;

    /**
     * @var
     */
    private $server;

    /**
     * OAuthService constructor.
     * @param EntityManager $entityManager
     * @param array $config
     */
    public function __construct(EntityManager $entityManager, array $config) {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    /**
     *
     */
    public function initServer() {
        $clientStorage = $this->entityManager->getRepository(Client::class);
        $userStorage = $this->entityManager->getRepository(User::class);
        $accessTokenStorage = $this->entityManager->getRepository(AccessToken::class);
        $refreshTokenStorage = $this->entityManager->getRepository(RefreshToken::class);

        $server = new Server([
            'client_credentials' => $clientStorage,
            'user_credentials' => $userStorage,
            'access_token' => $accessTokenStorage,
            'refresh_token' => $refreshTokenStorage,
        ], [
            'auth_code_lifetime' => $this->config['auth_code_lifetime'],
            'refresh_token_lifetime' => $this->config['refresh_token_lifetime'],
        ]);

        $server->addGrantType(new ClientCredentials($clientStorage));
        $server->addGrantType(new UserCredentialsGrantType($userStorage));
        $server->addGrantType(new OAuthRefreshToken($refreshTokenStorage));
        $server->addGrantType(new OAuthRefreshToken($refreshTokenStorage, [
            'always_issue_new_refresh_token' => $this->config['always_issue_new_refresh_token'],
        ]));

        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    public function handleRequest() {
        $this->server->handleTokenRequest(Request::createFromGlobals())->send();
    }
}