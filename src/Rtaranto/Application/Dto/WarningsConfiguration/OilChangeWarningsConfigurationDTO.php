<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

class OilChangeWarningsConfigurationDTO
{
    private $isActive;
    private $kmsPerOilChange;
    private $kmsInAdvance;
    
    public function __construct($isActive, $kmsPerOilChange, $kmsInAdvance)
    {
        $this->isActive = $isActive;
        $this->kmsPerOilChange = $kmsPerOilChange;
        $this->kmsInAdvance = $kmsInAdvance;
    }
    
    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getKmsPerOilChange()
    {
        return $this->kmsPerOilChange;
    }

    public function getKmsInAdvance()
    {
        return $this->kmsInAdvance;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function setKmsPerOilChange($kmsPerOilChange)
    {
        $this->kmsPerOilChange = $kmsPerOilChange;
    }

    public function setKmsInAdvance($kmsInAdvance)
    {
        $this->kmsInAdvance = $kmsInAdvance;
    }
}
