<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\PerformedOilChange;

interface OilChangeRepositoryInterface
{
    public function update(OilChange $oilChange);
    public function findOneByMotorcycle($motorcycle);
    public function findOneByPerformedMaintenance(PerformedOilChange $performedMaintenance);
}
