<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

class MaintenanceWarningConfigurationDTO
{
    private $isActive;
    private $kmsPerMaintenance;
    private $kmsInAdvance;
    
    public function __construct($isActive, $kmsPerMaintenance, $kmsInAdvance)
    {
        $this->isActive = $isActive;
        $this->kmsPerMaintenance = $kmsPerMaintenance;
        $this->kmsInAdvance = $kmsInAdvance;
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
