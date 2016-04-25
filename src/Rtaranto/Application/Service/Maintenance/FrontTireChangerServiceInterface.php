<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface FrontTireChangerServiceInterface
{
    public function changeFrontTire($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
