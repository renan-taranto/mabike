<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Domain\Entity\PerformedOilChange;

interface PerformedOilChangePatcherInterface
{
    public function patchPerformedOilChange(
        PerformedOilChange $performedOilChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
