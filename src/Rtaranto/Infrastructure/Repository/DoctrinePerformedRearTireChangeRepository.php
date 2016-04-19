<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;

class DoctrinePerformedRearTireChangeRepository extends DoctrinePerformedMaintenanceRepository implements PerformedRearTireChangeRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function update(PerformedRearTireChange $performedRearTireChange)
    {
        $this->em->flush($performedRearTireChange);
        return $performedRearTireChange;
    }
    
    public function delete(PerformedRearTireChange $performedRearTireChange)
    {
        $this->em->remove($performedRearTireChange);
        $this->em->flush();
    }

    protected function getEntityClassName()
    {
        return PerformedRearTireChange::class;
    }
}
