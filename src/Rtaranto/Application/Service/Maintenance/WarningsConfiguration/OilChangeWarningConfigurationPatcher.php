<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\OilChangeWarningConfigurationsDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\MaintenanceWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class OilChangeWarningConfigurationPatcher extends MaintenanceWarningConfigurationPatcher
{
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository,
        ValidatorInterface $validator
    ) {
        parent::__construct($maintenanceWarningObserverRepository, $maintenanceRepository, $validator);
    }
    
    protected function createPatchedMaintenanceWarningConfigurationDTO(
        $motorcycleId,
        Maintenance $patchedMaintenance,
        MaintenanceWarningObserver $patchedMaintenanceWarningObserver
    ) {
        $isActive = $patchedMaintenanceWarningObserver->isActive();
        $kmsPerMaintenance = $patchedMaintenance->getKmsPerMaintenance();
        $kmsInAdvance = $patchedMaintenanceWarningObserver->getKmsInAdvance();
        return new OilChangeWarningConfigurationsDTO($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
