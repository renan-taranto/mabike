<?php
namespace Rtaranto\Domain\Entity\Factory;

use Rtaranto\Domain\Entity\Biker;

class MotorcycleRegistrationService
{
    private $motorcycleFactory;
    
    public function __construct(MotorcycleFactory $motorcycleFactory, MaintenanceRegistrationService $maintenanceRegistrat)
    {
        $this->motorcycleFactory = $motorcycleFactory;
    }
    
    public function registerMotorcycle($model, $kmsDriven = 0)
    {
        $motorcycle = new Motorcycle($model, $kmsDriven);
        $this->biker->addMotorcycle($motorcycle);
        $this->maintenanceRegistrationService->registerMaintenancesPerformers($motorcycle);
    }

}
