<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface FrontTireChangerServiceInterface
{
    public function changeFrontTire($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
