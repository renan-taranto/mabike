<?php
namespace Rtaranto\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract class MaintenancePerformer
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var ArrayCollection
     */
    protected $maintenancesPerformed;
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
        if (empty($this->maintenancesPerformed)) {
            return;
        }
        
        /* @var $lastMaintenancePerformed Maintenance */
        $lastMaintenancePerformed = $this->maintenancesPerformed[0];
        /* @var $maintenance Maintenance */
        foreach ($this->maintenancesPerformed as $maintenance) {
            if ($maintenance->getKmsDriven() > $lastMaintenancePerformed->getKmsDriven()) {
                $lastMaintenancePerformed = $maintenance;
            }
        }
        
        return $lastMaintenancePerformed->getKmsDriven();
    }
    
    /**
     * @param Maintenance $maintenance
     */
    protected function addMaintenancePerformed(Maintenance $maintenance)
    {
        $this->maintenancesPerformed->add($maintenance);
    }
}
