<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface OilChangerInterface
{
    public function changeOil($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
