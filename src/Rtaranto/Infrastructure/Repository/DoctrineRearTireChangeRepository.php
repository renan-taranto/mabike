<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class DoctrineRearTireChangeRepository implements RearTireChangeRepositoryInterface
{
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function findOneByMotorcycle($motorcycle)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle)
        );
    }

    public function findOneByPerformedMaintenance(PerformedRearTireChange $performedMaintenance)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('m')
            ->from(RearTireChange::class, 'm')
            ->join('m.performedMaintenances', 'pm')
            ->where('pm.id = :pmId')
            ->setParameter('pmId', $performedMaintenance->getId());
        return $qb->getQuery()->getSingleResult();
    }
    
    public function update(RearTireChange $rearTireChange)
    {
        $this->em->flush($rearTireChange);
        return $rearTireChange;
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(RearTireChange::class);
    }

}
