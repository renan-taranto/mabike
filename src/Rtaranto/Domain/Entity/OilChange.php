<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
 
class OilChange extends Maintenance
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
