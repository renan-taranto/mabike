<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class DoctrineOilChangeRepository implements OilChangeRepositoryInterface
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
    
    public function findOneByPerformedMaintenance(PerformedOilChange $performedMaintenance)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('m')
            ->from(OilChange::class, 'm')
            ->join('m.performedMaintenances', 'pm')
            ->where('pm.id = :pmId')
            ->setParameter('pmId', $performedMaintenance->getId());
        return $qb->getQuery()->getSingleResult();
    }

    public function update(OilChange $oilChange)
    {
        $this->em->flush($oilChange);
        return $oilChange;
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(OilChange::class);
    }
}
