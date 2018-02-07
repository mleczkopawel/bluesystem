<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 04.02.18
 * Time: 16:49
 */

namespace Log\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class LogsRepository
 * @package Log\Repository
 */
class MainLogRepository extends EntityRepository {

    /**
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    public function countDistinctLogs() {
        $stmt = 'SELECT DISTINCT discrimer FROM logs';

        $query = $this->getEntityManager()->getConnection()->prepare($stmt);
        $query->execute();

        return count($query->fetchAll(\PDO::FETCH_ASSOC));
    }

}