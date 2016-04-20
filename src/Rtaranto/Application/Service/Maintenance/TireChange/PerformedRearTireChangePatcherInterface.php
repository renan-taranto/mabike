<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Domain\Entity\PerformedRearTireChange;

interface PerformedRearTireChangePatcherInterface
{
    public function patchPerformedRearTireChange(
        PerformedRearTireChange $performedRearTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
