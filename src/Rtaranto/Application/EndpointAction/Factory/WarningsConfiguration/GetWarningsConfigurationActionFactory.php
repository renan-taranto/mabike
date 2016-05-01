<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceWarninObserverRepository;

abstract class GetWarningsConfigurationActionFactory implements GetWarningsConfigurationActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function createGetAction($maintenanceClassName, $maintenanceWarningObserverClassName)
    {
        $maintenanceWarninObserverRepository = new DoctrineMaintenanceWarninObserverRepository(
            $this->em,
            $maintenanceWarningObserverClassName
        );
        
        $maitenanceRepository = new DoctrineMaintenanceRepository($this->em, $maintenanceClassName);
        $maintenanceWarningConfigurationDTOFactory = $this->createMaintenanceWarningConfigurationsDTOFactory(
            $maintenanceWarninObserverRepository,
            $maitenanceRepository
        );
        
        return new GetWarningsConfigurationAction($maintenanceWarningConfigurationDTOFactory);
    }
    
    abstract protected function createMaintenanceWarningConfigurationsDTOFactory(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    );
}
