<?php
namespace Rtaranto\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;

abstract class Maintenance
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var ArrayCollection
     */
    protected $performedMaintenances;
    
    /**
     * @var int
     */
    protected $kmsPerMaintenance;
    
    /**
     * @var Motorcycle
     */
    protected $motorcycle;
    
    /**
     * @param Motorcycle $motorcycle
     * @param int $kmsPerMaintenance
     */
    public function __construct(Motorcycle $motorcycle, $kmsPerMaintenance = null)
    {
        $this->performedMaintenances = new ArrayCollection();
        $this->motorcycle = $motorcycle;    
        $this->setKmsPerMaintenance($kmsPerMaintenance);
    }
    
    /**
     * @param PerformedMaintenance $performedMaintenance
     */
    protected function addPerformedMaintenance(PerformedMaintenance $performedMaintenance)
    {
        $this->performedMaintenances->add($performedMaintenance);
        $this->notifyMotorcyleMaintenanceWarningObservers();
    }
    
    public function removePerformedMaintenance(PerformedMaintenance $performedMaintenance)
    {
        $this->performedMaintenances->removeElement($performedMaintenance);
        $this->notifyMotorcyleMaintenanceWarningObservers();
    }
    
    /**
     * @param int $kms
     * @throws Exception
     */
    public function setKmsPerMaintenance($kms)
    {
        if (is_null($kms)) {
            return;
        }
        if ((int)$kms != $kms or (int)$kms < 1) {
            throw new Exception('KmsPerMaintenance must be an int value greater than 0.');
        }
        $this->kmsPerMaintenance = $kms;
        $this->notifyMotorcyleMaintenanceWarningObservers();
    }
    
    public function getKmsPerMaintenance()
    {
        return $this->kmsPerMaintenance;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getKmsForNextMaintenancePerforming()
    {
        if (empty($this->kmsPerMaintenance)) {
            throw new Exception('Unable to calculate kms for next maintenance. '
                . 'Property $kmsPerMaintenance must be set by calling setKmsPerMaintenance() method.'
            );
        }
        
        $kmsDrivenAtLastMaintenance = $this->getKmsDrivenAtLastMaintenance();
        if (is_null($kmsDrivenAtLastMaintenance)) {
            throw new Exception('Unable to calculate kms for next maintenance '
                . 'since no maintenance has been performed yet.'
            );
        }
        
        return $kmsDrivenAtLastMaintenance + $this->kmsPerMaintenance;
    }
    
    /**
     * @return int kms
     */
    protected function getKmsDrivenAtLastMaintenance()
    {
        if ($this->performedMaintenances->isEmpty()) {
            return;
        }
        
        /* @var $lastPerformedMaintenancePerformed Maintenance */
        $lastMaintenancePerformed = $this->performedMaintenances[0];
        /* @var $maintenance PerformedMaintenance */
        foreach ($this->performedMaintenances as $maintenance) {
            if ($maintenance->getKmsDriven() > $lastMaintenancePerformed->getKmsDriven()) {
                $lastMaintenancePerformed = $maintenance;
            }
        }
        
        return $lastMaintenancePerformed->getKmsDriven();
    }
    
    protected function throwExceptionIfMaintenanceKmsExceedsMotorcycleKms($maintenanceKms)
    {
        if ($maintenanceKms > $this->motorcycle->getKmsDriven()) {
            throw new \Exception('Maintenance kms exceeds current motorcycle '
                . 'kms driven. Update motorcycle kms driven if needed before'
                . 'trying again.');
        }
    }
    
    public function notifyMotorcyleMaintenanceWarningObservers()
    {
        $this->motorcycle->notifyMaintenanceWarningObservers();
    }
}
