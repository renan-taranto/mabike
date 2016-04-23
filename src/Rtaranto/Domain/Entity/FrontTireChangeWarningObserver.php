<?php
namespace Rtaranto\Domain\Entity;

class FrontTireChangeWarningObserver extends MaintenanceWarningObserver
{
    private static $WARNING_DESCRIPTION = 'Rear Tire Change';
    
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