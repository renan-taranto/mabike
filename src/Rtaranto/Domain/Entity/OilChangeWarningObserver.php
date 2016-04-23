<?php
namespace Rtaranto\Domain\Entity;

class OilChangeWarningObserver extends MaintenanceWarningObserver
{
    private static $WARNING_DESCRIPTION = 'Oil Change';
    
    public function __construct(
        Motorcycle $motorcycle,
        Maintenance $maintenance,
        $numKmsBeforeMaintenancePerforming = 0
    ) {
        parent::__construct($motorcycle, $maintenance, $numKmsBeforeMaintenancePerforming);
    }

    protected function getWarningDescription()
    {
        return self::$WARNING_DESCRIPTION;
    }
}
