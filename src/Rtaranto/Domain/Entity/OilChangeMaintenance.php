<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class OilChangeMaintenance extends Maintenance implements OilChangerInterface
{
    public function __construct(Motorcycle $motorcycle, $kmsPerOilChange = null)
    {
        $this->performedMaintenances = new ArrayCollection();
        $this->motorcycle = $motorcycle;    
        $this->kmsPerMaintenance = $kmsPerOilChange;
    }
    
    /**
     * @param int $kmsDriven
     * @param DateTime $date
     * @return PerformedOilChange
     */
    public function changeOil($kmsDriven = null, DateTime $date = null)
    {
        if (empty($kmsDriven)) {
            $kmsDriven = $this->motorcycle->getKmsDriven();
        }
        if (empty($date)) {
            $date = new DateTime('now');
        }
        
        $performedOilChange = new PerformedOilChange($this, $kmsDriven, $date);
        
        $this->addPerformedMaintenance($performedOilChange);
        
        return $performedOilChange;
    }
    
    public function getKmsForNextMaintenance()
    {
        if (empty($this->kmsPerMaintenance)) {
            throw new Exception('Unable to calculate kms for next oil change. '
                . 'Property $kmsPerMaintenance must be set by calling setKmsPerMaintenance() method.'
            );
        }
        
        $kmsDrivenAtLastMaintenance = $this->getKmsDrivenAtLastMaintenance();
        if (empty($kmsDrivenAtLastMaintenance)) {
            throw new Exception('Unable to calculate kms for next oil change '
                . 'since no oil change has been performed. '
                . 'Perform oil changes by calling changeOil() method.'
            );
        }
        
        return $kmsDrivenAtLastMaintenance + $this->kmsPerMaintenance;
    }

}
