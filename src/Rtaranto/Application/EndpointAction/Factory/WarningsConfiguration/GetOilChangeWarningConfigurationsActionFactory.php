<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\Dto\WarningsConfiguration\OilChangeWarningConfigurationsDTOFactory;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class GetOilChangeWarningConfigurationsActionFactory extends GetWarningsConfigurationActionFactory
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }
    
    protected function createMaintenanceWarningConfigurationsDTOFactory(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    ) {
        return new OilChangeWarningConfigurationsDTOFactory(
            $maintenanceWarningObserverRepository,
            $maintenanceRepository
        );
    }
}
