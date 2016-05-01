<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

class RearTireChangeWarningConfigurationsDTO extends MaintenanceWarningConfigurationsDTO
{
    public function __construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        parent::__construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
