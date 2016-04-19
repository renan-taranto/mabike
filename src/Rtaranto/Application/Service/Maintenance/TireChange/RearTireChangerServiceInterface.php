<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface RearTireChangerServiceInterface
{
    public function changeRearTire($motorcycleId, PerformedMaintenanceDTO $performedRearTireChangeDTO);
}
