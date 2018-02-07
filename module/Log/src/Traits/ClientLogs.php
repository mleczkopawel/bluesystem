<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 22:05
 */

namespace Log\Traits;


use Doctrine\Common\Collections\ArrayCollection;
use Log\Entity\MainLog;

/**
 * Trait ClientLogs
 * @package Log\Traits
 */
trait ClientLogs {

    /**
     * @ORM\ManyToMany(targetEntity="Log\Entity\MainLog", inversedBy="clients")
     * @ORM\JoinTable(name="clients_logs")
     */
    private $logs;

    /**
     * ClientLogs constructor.
     */
    public function __construct() {
        $this->logs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     * @return ClientLogs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * @param MainLog $log
     */
    public function addLog(MainLog $log) {
        $log->addClient($this);
        $this->logs[] = $log;
    }
}