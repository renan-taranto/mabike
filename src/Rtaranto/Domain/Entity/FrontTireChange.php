<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

class FrontTireChange extends Maintenance implements FrontTireChangerInterface
{
    public function __construct(Motorcycle $motorcycle, $kmsPerMaintenance = null)
    {
        parent::__construct($motorcycle, $kmsPerMaintenance);
    }
    
    public function changeFrontTire($kmsDriven = null, DateTime $date = null)
    {
        if (is_null($kmsDriven)) {
            $kmsDriven = $this->motorcycle->getKmsDriven();
        }
        if (empty($date)) {
            $date = new DateTime('now');
        }
        
        $performedFrontTireChange = new PerformedFrontTireChange($this->motorcycle, $kmsDriven, $date);
        
        $this->addPerformedMaintenance($performedFrontTireChange);
        
        return $performedFrontTireChange;
    }
}
