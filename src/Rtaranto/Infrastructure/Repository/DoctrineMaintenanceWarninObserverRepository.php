<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\MaintenanceWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class DoctrineMaintenanceWarninObserverRepository implements MaintenanceWarningObserverRepositoryInterface
{
    private $em;
    private $subClassName;
    
    public function __construct(EntityManagerInterface $em, $subClassName)
    {
        $this->em = $em;
        $this->subClassName = $subClassName;
    }
    
    public function findOneByMotorcycle($motorcycle)
    {
        $objectRepository = $this->getObjectRepository();
        return $objectRepository->findOneBy(array('motorcycle' => $motorcycle));
    }
    
    /**
     * @return ObjectRepository
     */
    private function getObjectRepository()
    {
        return $this->em->getRepository($this->subClassName);
    }

    public function update(MaintenanceWarningObserver $maintenanceWarningObserver)
    {
        $this->em->persist($maintenanceWarningObserver);
        $this->em->flush();
        return $maintenanceWarningObserver;
    }

}
