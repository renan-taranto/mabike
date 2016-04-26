<?php
namespace Rtaranto\Domain\Entity;

use Exception;

abstract class MaintenanceWarningObserver
{
    private $id;
    /**
     * @var int Number of kms to activate the warning before the next maintenance
     * to be performed
     */
    protected $kmsInAdvance;
    
    /**
     * @var Motorcycle
     */
    protected $motorcycle;
    
    /**
     * @var Maintenance
     */
    protected $maintenance;
    
    /**
     * @var MaintenanceWarning
     */
    protected $maintenanceWarning;
    
    /**
     * @var boolean
     */
    protected $isActive;
    
    public function __construct(Motorcycle $motorcycle, Maintenance $maintenance, $kmsBeforeNextMaintenance = 0)
    {
        $this->motorcycle = $motorcycle;
        $this->maintenance = $maintenance;
        $this->kmsInAdvance = $kmsBeforeNextMaintenance;
        $this->isActive = false;
        $this->notify();
    }
    
    public function activate()
    {
        $this->isActive = true;
        $this->notify();
    }
    
    public function deactivate()
    {
        $this->isActive = false;
    }
    
    public function isActive()
    {
        return $this->isActive;
    }
    
    public function notify()
    {

        if (!$this->isActive) {
            return;
        }
        
        if (!$this->warningShouldBeTriggered()) {
            $this->maintenanceWarning = null;
            return;
        }
        
        if (is_null($this->maintenanceWarning)) {
            $this->maintenanceWarning = $this->createWarning();
            return;
        }
        
        $this->updateWarningIfNeeded();
    }
    
    protected function warningShouldBeTriggered()
    {
        try {
            $triggeringKms = $this->getWarningActivationKms();
        } catch (Exception $ex) {
            return false;
        }
        return $this->motorcycle->getKmsDriven() >= $triggeringKms;
    }
    
    protected function createWarning()
    {
        $warningDescription = $this->getWarningDescription();
        $kmsForNextMaintenancePerforming = $this->maintenance->getKmsForNextMaintenancePerforming();
        return new MaintenanceWarning($warningDescription, $kmsForNextMaintenancePerforming);
    }
    
    protected function updateWarningIfNeeded()
    {
        $kmsForNextMaintenancePerforming = $this->maintenance->getKmsForNextMaintenancePerforming();
        $curWarningKmsForNextMaintenancePerforming = $this->maintenanceWarning->getAtKms();
        if ($kmsForNextMaintenancePerforming != $curWarningKmsForNextMaintenancePerforming) {
            $this->maintenanceWarning = $this->createWarning();
        }
    }
    
    protected function getWarningActivationKms()
    {
        try {
            $kmsForNextMaintenance = $this->maintenance->getKmsForNextMaintenancePerforming();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $kmsForNextMaintenance - $this->kmsInAdvance;
    }
    
    public function getWarning()
    {
        if ($this->isActive) {
            return $this->maintenanceWarning;
        }
    }
    
    public function setKmsInAdvance($kmsInAdvance)
    {
        $this->kmsInAdvance = $kmsInAdvance;
        $this->notify();
    }
    
    public function getKmsInAdvance()
    {
        return $this->kmsInAdvance;
    }

    abstract protected function getWarningDescription();
}