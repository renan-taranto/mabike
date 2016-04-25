<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface RearTireChangerServiceInterface
{
    public function changeRearTire($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
