<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

class OilChangeWarningConfigurationsDTO extends MaintenanceWarningConfigurationsDTO
{
    public function __construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        parent::__construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
