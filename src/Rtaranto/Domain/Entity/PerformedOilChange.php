<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
 
class PerformedOilChange extends PerformedMaintenance
{
    /**
     * @param int $kmsDriven
     * @param DateTime $date
     */
    public function __construct($kmsDriven, DateTime $date)
    {
        $this->kmsDriven = $kmsDriven;
        $this->date = $date;
    }
}
