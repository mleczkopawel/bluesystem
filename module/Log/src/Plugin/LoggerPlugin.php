<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 03.02.18
 * Time: 23:34
 */

namespace Log\Plugin;


use Auth\Entity\User;
use Doctrine\ORM\EntityManager;
use Log\Entity\SystemLog;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Stdlib\SplPriorityQueue;

/**
 * Class LoggerPlugin
 * @package Log\Plugin
 */
class LoggerPlugin extends AbstractPlugin {
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var array
     */
    private $path;


    /**
     * LoggerPlugin constructor.
     * @param Logger $logger
     * @param EntityManager $entityManager
     * @param array $path
     */
    public function __construct(Logger $logger,
                                EntityManager $entityManager,
                                array $path) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->path = $path;
    }

    /**
     * @param string $fileName
     * @param string $logText
     * @param int $priority
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function log(string $fileName, string $logText, int $priority) {
        $user = $this->entityManager->getRepository(User::class)->find(1);
        $systemLog = new SystemLog();
        $systemLog
            ->setStatus($priority)
            ->setDesc($logText)
            ->setDateAdd(new \DateTime())
            ->setName($fileName);
        $user->addLog($systemLog);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $writer = new Stream($this->path['path'] . $fileName . '.txt');
        $logQueue = new SplPriorityQueue();
        $logQueue->insert($writer, 1);

        $this->logger->setWriters($logQueue);
        $this->logger->log($priority, $logText);
    }
}