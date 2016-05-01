<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

class FrontTireChangeWarningConfigurationsDTO extends MaintenanceWarningConfigurationsDTO
{
    public function __construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        parent::__construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
