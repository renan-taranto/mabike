<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

abstract class DoctrinePerformedMaintenanceRepository implements PerformedMaintenanceRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filters, $orderBy, $limit, $offset);
    }

    /**
     * @param Motorcycle|id $motorcycle
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllByMotorcycle($motorcycle, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filtersContainingMotorcycleFilter = array_merge($filters, array('motorcycle' => $motorcycle));
        return $this->findAll($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    public function findOneByMotorcycleAndId($motorcycle, $performedRearTireChange)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle, 'id' => $performedRearTireChange)
        );
    }

    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        $entityClassName = $this->getEntityClassName();
        return $this->em->getRepository($entityClassName);
    }
    
    abstract protected function getEntityClassName();
}
