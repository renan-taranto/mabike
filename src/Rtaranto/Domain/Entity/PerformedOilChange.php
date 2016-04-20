<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
 
class PerformedOilChange extends PerformedMaintenance
{
    public function __construct(Motorcycle $motorcycle, $kmsDriven, DateTime $date)
    {
        parent::__construct($motorcycle, $kmsDriven, $date);
    }
}
