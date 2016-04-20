<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class RearTireChange extends Maintenance implements RearTireChangerInterface
{
    /**
     * @param Motorcycle $motorcycle
     * @param type $kmsPerOilChange
     */
    public function __construct(Motorcycle $motorcycle, $kmsPerOilChange = null)
    {
        $this->performedMaintenances = new ArrayCollection();
        $this->motorcycle = $motorcycle;    
        $this->kmsPerMaintenance = $kmsPerOilChange;
    }
    
    /**
     * @param int $kmsDriven
     * @param DateTime $date
     * @return PerformedRearTireChange
     */
    public function changeRearTire($kmsDriven = null, DateTime $date = null)
    {
        if (is_null($kmsDriven)) {
            $kmsDriven = $this->motorcycle->getKmsDriven();
        }
        if (empty($date)) {
            $date = new DateTime('now');
        }
        
        $performedRearTireChange = new PerformedRearTireChange($this->motorcycle, $kmsDriven, $date);
        
        $this->addPerformedMaintenance($performedRearTireChange);
        
        return $performedRearTireChange;
    }
    
    /**
     * @return int
     * @throws Exception
     */
    public function getKmsForNextMaintenance()
    {
        if (empty($this->kmsPerMaintenance)) {
            throw new Exception('Unable to calculate kms for next maintenance. '
                . 'Property $kmsPerMaintenance must be set by calling setKmsPerMaintenance() method.'
            );
        }
        
        $kmsDrivenAtLastMaintenance = $this->getKmsDrivenAtLastMaintenance();
        if (empty($kmsDrivenAtLastMaintenance)) {
            throw new Exception('Unable to calculate kms for next maintenance '
                . 'since no maintenance has been performed yet.'
            );
        }
        
        return $kmsDrivenAtLastMaintenance + $this->kmsPerMaintenance;
    }
}
