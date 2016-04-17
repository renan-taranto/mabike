<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface OilChangePosterInterface
{
    public function postOilChange($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
