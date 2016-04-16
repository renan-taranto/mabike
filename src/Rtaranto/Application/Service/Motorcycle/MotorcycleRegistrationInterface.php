<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Rtaranto\Domain\Entity\Biker;

interface MotorcycleRegistrationInterface
{
    public function registerMotorcycle(Biker $biker, $model, $kmsDriven = 0);
}
