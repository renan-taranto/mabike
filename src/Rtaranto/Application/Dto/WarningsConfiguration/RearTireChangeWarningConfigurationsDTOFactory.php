<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class RearTireChangeWarningConfigurationsDTOFactory extends MaintenanceWarningConfigurationsDTOFactory
{
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    ) {
        parent::__construct($maintenanceWarningObserverRepository, $maintenanceRepository);
    }
    
    public function createMaintenanceConfigurationDTO($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        return new RearTireChangeWarningConfigurationsDTO($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
