<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
 
class PerformedOilChange extends PerformedMaintenance
{
    /**
     * @param Maintenance $motorcycle
     * @param int $kmsDriven
     * @param DateTime $date
     */
    public function __construct(Motorcycle $motorcycle, $kmsDriven, DateTime $date)
    {
        $this->motorcycle = $motorcycle;
        $this->kmsDriven = $kmsDriven;
        $this->date = $date;
    }
}
