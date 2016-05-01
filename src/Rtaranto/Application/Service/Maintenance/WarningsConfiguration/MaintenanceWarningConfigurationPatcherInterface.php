<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationsDTO;

interface MaintenanceWarningConfigurationPatcherInterface
{
    public function patchMaintenanceWarningConfiguration(
        $motorcycleId,
        MaintenanceWarningConfigurationsDTO $maintenanceWarningsConfigurationDTO
    );
}
