<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 22:10
 */

namespace Log\Traits;


use Auth\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Log\Entity\MainLog;

/**
 * Trait UserLogs
 * @package Log\Traits
 */
trait UserLogs {

    /**
     * @ORM\ManyToMany(targetEntity="Log\Entity\MainLog", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="users_logs")
     */
    private $logs;

    /**
     * UserLogs constructor.
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
     * @return User
     */
    public function setLogs($logs): User
    {
        $this->logs = $logs;
        return $this;
    }

    /**
     * @param MainLog $log
     */
    public function addLog(MainLog $log) {
        $log->addUser($this);
        $this->logs[] = $log;
    }
}