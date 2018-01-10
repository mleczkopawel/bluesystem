<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 05.01.18
 * Time: 21:39
 */

namespace Auth\Traits;


/**
 * Trait ArrayOperations
 * @package Auth\Traits
 */
/**
 * Trait ArrayOperations
 * @package Auth\Traits
 */
trait ArrayOperations
{
    /**
     * @return array
     */
    public function toArray():array {
        $data = get_object_vars($this);

        return $data;
    }

    /**
     * @param array $params
     * @return self
     */
    public static function fromArray(array $params): self {
        $object = new self();
        foreach ($params as $property => $value) {
            $object->$property = $value;
        }

        return $object;
    }
}