<?php
namespace Rtaranto\Domain\Entity;

class PerformedFrontTireChange extends PerformedMaintenance
{
    public function __construct(Motorcycle $motorcycle, $kmsDriven, \DateTime $date)
    {
        parent::__construct($motorcycle, $kmsDriven, $date);
    }
}
