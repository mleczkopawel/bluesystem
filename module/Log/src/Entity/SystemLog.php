<?php

namespace Log\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SystemLog
 * @package Log\Entity
 *
 * @ORM\Table(name="log_system")
 * @ORM\Entity
 */
class SystemLog extends MainLog
{

    const ERROR = 3;

    const WARNING = 4;

    const NOTICE = 5;

    const INFO = 6;

    const STATUS_MAP = [
        3 => 'error',
        'warning',
        'notice',
        'info',
    ];

    private $typeName = 'SYSTEM';

    private $sortNumber = 1;

    private $codeName = 'system';

    /**
     * @var string
     *
     * @ORM\Column(name="log_desc", type="string", length=255, nullable=false)
     */
    private $desc = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_log", type="integer", nullable=false)
     */
    private $status = null;

    /**
     * @return string
     */
    public function getDesc() : string
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     * @return SystemLog
     */
    public function setDesc(string $desc) : \Log\Entity\SystemLog
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return SystemLog
     */
    public function setStatus(int $status) : \Log\Entity\SystemLog
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getSortNumber() : int
    {
        return $this->sortNumber;
    }

    /**
     * @return string
     */
    public function getTypeName() : string
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function getCodeName() : string
    {
        return $this->codeName;
    }


}

