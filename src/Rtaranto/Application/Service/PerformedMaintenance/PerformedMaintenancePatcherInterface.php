<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Domain\Entity\PerformedMaintenance;

interface PerformedMaintenancePatcherInterface
{
    public function patchPerformedMaintenance(
        PerformedMaintenance $performedMaintenance,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
