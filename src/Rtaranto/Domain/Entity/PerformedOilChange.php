<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
 
class PerformedOilChange extends PerformedMaintenance
{
    /**
     * @param Maintenance $maintenance
     * @param int $kmsDriven
     * @param DateTime $date
     */
    public function __construct(Maintenance $maintenance, $kmsDriven, DateTime $date)
    {
        $this->kmsDriven = $kmsDriven;
        $this->date = $date;
        $this->maintenance = $maintenance;
    }
}
