<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class DoctrineRearTireChangeRepository extends DoctrineMaintenanceRepository implements RearTireChangeRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function update(RearTireChange $rearTireChange)
    {
        $this->em->flush($rearTireChange);
        return $rearTireChange;
    }

    protected function getEntityClassName()
    {
        return RearTireChange::class;
    }
}
