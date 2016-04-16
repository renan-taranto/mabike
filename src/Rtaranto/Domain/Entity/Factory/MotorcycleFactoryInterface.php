<?php
namespace Rtaranto\Domain\Entity\Factory;

interface MotorcycleFactoryInterface
{
    public function createMotorcycle($model, $kmsDriven = 0);
}
