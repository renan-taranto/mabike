<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class DoctrinePerformedMaintenanceRepository extends DoctrineRepository implements PerformedMaintenanceRepositoryInterface
{
    public function __construct(EntityManagerInterface $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }
    
    public function update($performedMaintenance)
    {
        $this->em->flush($performedMaintenance);
        return $performedMaintenance;
    }
    
    public function delete($performedMaintenance)
    {
        $this->em->remove($performedMaintenance);
        $this->em->flush();
    }
    

    public function findAllByMotorcycle($motorcycle, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filtersContainingMotorcycleFilter = array_merge(
            $filters,
            array('motorcycle' => $motorcycle)
        );
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    public function findOneByMotorcycleAndId($motorcycle, $performedMaintenanceId)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(array('motorcycle' => $motorcycle, 'id' => $performedMaintenanceId));
    }

}
