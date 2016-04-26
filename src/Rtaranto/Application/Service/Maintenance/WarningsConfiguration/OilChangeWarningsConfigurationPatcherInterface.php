<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationDTO;

interface OilChangeWarningsConfigurationPatcherInterface
{
    public function patchOilChangeWarningsConfiguration(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $oilChangeWarningsConfigurationDTO
    );
}
