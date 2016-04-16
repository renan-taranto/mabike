<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Domain\Entity\Motorcycle;

interface MotorcyclePatcherInterface
{
    public function patchMotorcycle(Motorcycle $motorcycle, MotorcycleDTO $motorcycleDTO);
}
