<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceWarninObserverRepository;

class GetWarningsConfigurationActionFactory implements GetWarningsConfigurationActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function createGetAction($maintenanceClassName, $maintenanceWarningObserverClassName)
    {
        $oilChangeWarningObserverRepository = new DoctrineMaintenanceWarninObserverRepository(
            $this->em,
            $maintenanceWarningObserverClassName
        );
        $oilChangeRepository = new DoctrineMaintenanceRepository($this->em, $maintenanceClassName);
        return new GetWarningsConfigurationAction($oilChangeWarningObserverRepository, $oilChangeRepository);
    }
}
