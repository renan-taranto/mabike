<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationDTO;

interface MaintenanceWarningConfigurationPatcherInterface
{
    public function patchMaintenanceWarningConfiguration(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $maintenanceWarningsConfigurationDTO
    );
}
