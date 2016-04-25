<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface MaintenancePerformerServiceInterface
{
    public function performMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
