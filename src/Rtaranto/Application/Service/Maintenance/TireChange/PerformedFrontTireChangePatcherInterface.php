<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;

interface PerformedFrontTireChangePatcherInterface
{
    public function patchPerformedFrontTireChange(
        PerformedFrontTireChange $performedFrontTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
