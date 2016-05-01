<?php
namespace Rtaranto\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Motorcycle
 */
class Motorcycle
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $model;
    
    /**
     * @var int
     */
    private $kmsDriven;
    
    /**
     * @var Biker
     */
    private $biker;

    private $maintenanceWarningObservers;
    
    /**
     * @param int $model
     * @param int $kmsDriven
     */
    public function __construct($model, $kmsDriven = 0)
    {
        $this->model = $model;
        $this->setKmsDriven($kmsDriven);
        $this->maintenanceWarningObservers = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $kmsDriven
     */
    public function updateKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
        $this->notifyMaintenanceWarningObservers();
    }
    
    /**
     * @return int
     */
    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }
    
    private function setKmsDriven($kmsDriven)
    {
        if (empty($kmsDriven)) {
            $kmsDriven = 0;
        }
        $this->kmsDriven = $kmsDriven;
    }
    
    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Biker $biker
     */
    public function setBiker(Biker $biker)
    {
        $this->biker = $biker;
    }
    
    public function attachMaintenanceWarningObserver(MaintenanceWarningObserver $maintenanceObserver)
    {
        $this->maintenanceWarningObservers->add($maintenanceObserver);
    }
    
    public function dettachMaintenanceWarningObserver(MaintenanceWarningObserver $maintenanceObserver)
    {
        $this->maintenanceWarningObservers->removeElement($maintenanceObserver);
    }
    
    public function notifyMaintenanceWarningObservers()
    {
        if ($this->maintenanceWarningObservers->isEmpty()) {
            return;
        }
        
        /* @var $maintenanceWarningObserver MaintenanceWarningObserver */
        foreach ($this->maintenanceWarningObservers as $maintenanceWarningObserver) {
            $maintenanceWarningObserver->notify();
        }
    }
    
    public function getWarnings()
    {
        if ($this->maintenanceWarningObservers->isEmpty()) {
            return;
        }
        
        $warnings = array();
        /* @var $maintenanceWarningObserver MaintenanceWarningObserver */
        foreach ($this->maintenanceWarningObservers as $maintenanceWarningObserver) {
            $warning = $maintenanceWarningObserver->getWarning();
            $this->addWarningIfNotEmpty($warnings, $warning);
        }
        return $warnings;
    }
    
    private function addWarningIfNotEmpty(array &$warnings, $warning)
    {
        if (!empty($warning)) {
            array_push($warnings, $warning);
        }
    }
    
    public function hasWarnings()
    {
        return !empty($this->getWarnings());
    }
}
