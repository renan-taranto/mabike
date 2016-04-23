<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

class OilChange extends Maintenance implements OilChangerInterface
{
    public function __construct(Motorcycle $motorcycle, $kmsPerMaintenance = null)
    {
        parent::__construct($motorcycle, $kmsPerMaintenance);
    }
    
    /**
     * @param int $kmsDriven
     * @param DateTime $date
     * @return PerformedOilChange
     */
    public function changeOil($kmsDriven = null, DateTime $date = null)
    {
        if (is_null($kmsDriven)) {
            $kmsDriven = $this->motorcycle->getKmsDriven();
        }
        if (empty($date)) {
            $date = new DateTime('now');
        }
        $this->throwExceptionIfMaintenanceKmsExceedsMotorcycleKms($kmsDriven);
        
        $performedOilChange = new PerformedOilChange($this->motorcycle, $kmsDriven, $date);
        $this->addPerformedMaintenance($performedOilChange);
        return $performedOilChange;
    }
}

