<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 21:35
 */

namespace Auth\Entity;


/**
 * Class EncryptableField
 * @package Auth\Entity
 */
class EncryptableField {

    /**
     * @var array
     */
    protected $hashOptions = ['cost' => 11];

    /**
     * @param string $value
     * @return bool|string
     */
    protected function encryptField(string $value) {
        return password_hash($value, PASSWORD_BCRYPT, $this->hashOptions);
    }

    /**
     * @param string $encryptedValue
     * @param string $value
     * @return bool
     */
    protected function verifyEncryptableFieldValue(string $encryptedValue, string $value):bool {
        return password_verify($value, $encryptedValue);
    }

    public function check(string $encryptedValue, string $value):bool {
        return password_verify($value, $encryptedValue);
    }

}