<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface OilChangerServiceInterface
{
    public function changeOil($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
