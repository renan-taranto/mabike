<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\OilChangeWarningsConfigurationDTO;

interface OilChangeWarningsConfigurationPatcherInterface
{
    public function patchOilChangeWarningsConfiguration(
        $motorcycleId,
        OilChangeWarningsConfigurationDTO $oilChangeWarningsConfigurationDTO
    );
}
