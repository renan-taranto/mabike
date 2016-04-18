<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class DoctrinePerformedMaintenanceRepository extends EntityRepository implements PerformedMaintenanceRepositoryInterface 
{
    public function findAllByMaintenance(
        $maintenance,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $filters = array_merge(array('maintenance' => $maintenance), $filters);
        
        return $this->findBy($filters, $orderBy, $limit, $offset);
    }

    public function getByMaintenanceAndId($maintenance, $performedMaintenanceId)
    {
        return $this->findOneBy(array('maintenance' => $maintenance, 'id' => $performedMaintenanceId));
    }

}
