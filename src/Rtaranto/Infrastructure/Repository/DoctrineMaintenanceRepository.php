<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

abstract class DoctrineMaintenanceRepository implements MaintenanceRepositoryInterface
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
    
    public function findOneByMotorcycle($motorcycle)
    {
        $doctrineEntityRepository = $this->getDoctrineObjectRepository();
        return $doctrineEntityRepository->findOneBy(array('motorcycle' => $motorcycle));
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
