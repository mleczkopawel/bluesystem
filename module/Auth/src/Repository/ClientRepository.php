<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 21:58
 */

namespace Auth\Repository;


use Doctrine\ORM\EntityRepository;
use OAuth2\Storage\ClientCredentialsInterface;

/**
 * Class ClientRepository
 * @package Auth\Repository
 */
class ClientRepository extends EntityRepository implements ClientCredentialsInterface {

    /**
     * @param $client_id
     * @param null $client_secret
     * @return bool
     */
    public function checkClientCredentials($client_id, $client_secret = null) {
        $client = $this->findOneBy(['clientIdentifier' => $client_id]);
        if ($client) {
            return true;
        }

        return false;
    }

    /**
     * @param $client_id
     * @return bool
     */
    public function isPublicClient($client_id) {
        return false;
    }

    /**
     * @param $client_id
     * @return array|null|object
     */
    public function getClientDetails($client_id) {
        $client = $this->findOneBy(['clientIdentifier' => $client_id]);
        if ($client) {
            $client = $client->toArray();
        }

        return $client;
    }

    /**
     * @param $client_id
     * @return null
     */
    public function getClientScope($client_id) {
        return null;
    }

    /**
     * @param $client_id
     * @param $grant_type
     * @return bool
     */
    public function checkRestrictedGrantType($client_id, $grant_type) {
        return true;
    }
}