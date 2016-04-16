<?php
namespace Rtaranto\Domain\Entity;

interface OilChangerInterface
{
    public function changeOil($kmsDriven, \DateTime $date);
}
