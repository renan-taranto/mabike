<?php
namespace Rtaranto\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;

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
    
    abstract public function getKmsForNextMaintenance();
    
    /**
     * @param int $kms
     * @throws Exception
     */
    public function setKmsPerMaintenance($kms)
    {
        if ((int)$kms != $kms or (int)$kms < 1) {
            throw new Exception('KmsPerMaintenance must be an int value greater than 0.');
        }
        
        $this->kmsPerMaintenance = $kms;
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
    
    /**
     * @param PerformedMaintenance $performedMaintenance
     */
    protected function addPerformedMaintenance(PerformedMaintenance $performedMaintenance)
    {
        $this->performedMaintenances->add($performedMaintenance);
    }
}
