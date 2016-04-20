<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

interface FrontTireChangerInterface
{
    public function changeFrontTire($kmsDriven = null, DateTime $date = null);
}
