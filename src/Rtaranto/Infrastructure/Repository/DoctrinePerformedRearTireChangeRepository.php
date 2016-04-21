<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;

class DoctrinePerformedRearTireChangeRepository implements PerformedRearTireChangeRepositoryInterface
{
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param PerformedRearTireChange $performedRearTireChange
     * @return PerformedRearTireChange
     */
    public function update(PerformedRearTireChange $performedRearTireChange)
    {
        $this->em->flush($performedRearTireChange);
        return $performedRearTireChange;
    }
    
    /**
     * @param PerformedRearTireChange $performedRearTireChange
     */
    public function delete(PerformedRearTireChange $performedRearTireChange)
    {
        $this->em->remove($performedRearTireChange);
        $this->em->flush();
    }

    /**
     * @param object|int $motorcycle
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllByMotorcycle($motorcycle, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filtersContainingMotorcycleFilter = array_merge(
            $filters,
            array('motorcycle' => $motorcycle)
        );
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    /**
     * @param Motorcycle|int $motorcycle
     * @param int $performedRearTireChangeId
     * @return PerformedRearTireChange
     */
    public function findOneByMotorcycleAndId($motorcycle, $performedRearTireChangeId)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle, 'id' => $performedRearTireChangeId)
        );
    }

    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(PerformedRearTireChange::class);
    }
}
