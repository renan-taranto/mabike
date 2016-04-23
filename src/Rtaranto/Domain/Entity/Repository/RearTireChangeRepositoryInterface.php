<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\RearTireChange;

interface RearTireChangeRepositoryInterface
{
    public function update(RearTireChange $rearTireChange);
    public function findOneByMotorcycle($motorcycle);
    public function findOneByPerformedMaintenance(PerformedRearTireChange $performedMaintenance);
}
