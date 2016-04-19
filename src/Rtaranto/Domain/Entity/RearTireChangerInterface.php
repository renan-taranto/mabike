<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

interface RearTireChangerInterface
{
    public function changeRearTire($kmsDriven = null, DateTime $date = null);
}
