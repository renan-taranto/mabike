<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class RearTireChange extends Maintenance implements RearTireChangerInterface
{
    public function __construct(Motorcycle $motorcycle, $kmsPerMaintenance = null)
    {
        parent::__construct($motorcycle, $kmsPerMaintenance);
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
}
