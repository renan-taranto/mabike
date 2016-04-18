<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class DoctrinePerformedMaintenanceRepository extends EntityRepository implements PerformedMaintenanceRepositoryInterface 
{
    public function findAllByMaintenance($maintenance, $filters, $orderBy, $limit, $offset)
    {
        $filters = array_merge(array('maintenance' => $maintenance), $filters);
        
        return $this->findBy($filters, $orderBy, $limit, $offset);
    }
}
