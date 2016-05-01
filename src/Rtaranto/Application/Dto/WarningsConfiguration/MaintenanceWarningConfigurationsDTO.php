<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

abstract class MaintenanceWarningConfigurationsDTO
{
    protected $motorcycleId;
    protected $isActive;
    protected $kmsPerMaintenance;
    protected $kmsInAdvance;
    
    public function __construct($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        $this->motorcycleId = $motorcycleId;
        $this->isActive = $isActive;
        $this->kmsPerMaintenance = $kmsPerMaintenance;
        $this->kmsInAdvance = $kmsInAdvance;
    }
    
    public function getMotorcycleId()
    {
        return $this->motorcycleId;
    }

    public function setMotorcycleId($motorcycleId)
    {
        $this->motorcycleId = $motorcycleId;
    }

        public function getIsActive()
    {
        return $this->isActive;
    }

    public function getKmsPerMaintenance()
    {
        return $this->kmsPerMaintenance;
    }

    public function getKmsInAdvance()
    {
        return $this->kmsInAdvance;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function setKmsPerMaintenance($kmsPerMaintenance)
    {
        $this->kmsPerMaintenance = $kmsPerMaintenance;
    }

    public function setKmsInAdvance($kmsInAdvance)
    {
        $this->kmsInAdvance = $kmsInAdvance;
    }
}
