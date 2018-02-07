<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 04.02.18
 * Time: 00:20
 */

namespace Log\Traits;


/**
 * Trait Log
 * @package Log\Traits
 */
trait Log {

    /**
     * @return int
     */
    public function getSortNumber(): int
    {
        return $this->sortNumber;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function getCodeName(): string
    {
        return $this->codeName;
    }

}