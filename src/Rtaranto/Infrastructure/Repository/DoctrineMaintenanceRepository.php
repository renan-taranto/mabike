<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\PerformedMaintenance;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

class DoctrineMaintenanceRepository extends DoctrineRepository implements MaintenanceRepositoryInterface
{
    public function __construct(EntityManagerInterface $em, $entityClassName)
    {
        parent::__construct($em, $entityClassName);
    }

    public function update(Maintenance $maintenance)
    {
        $this->em->flush($maintenance);
        return $maintenance;
    }
    
    public function findOneByMotorcycle($motorcycle)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle)
        );
    }

    public function findOneByPerformedMaintenance(PerformedMaintenance $performedMaintenance)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('m')
            ->from($this->entityClassName, 'm')
            ->join('m.performedMaintenances', 'pm')
            ->where('pm.id = :pmId')
            ->setParameter('pmId', $performedMaintenance->getId());
        return $qb->getQuery()->getSingleResult();
    }
}
