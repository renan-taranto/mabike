<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;

interface OilChangerServiceInterface
{
    public function changeOil($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO);
}
