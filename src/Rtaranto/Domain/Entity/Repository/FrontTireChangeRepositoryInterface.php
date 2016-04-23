<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;

interface FrontTireChangeRepositoryInterface
{
    public function update(FrontTireChange $frontTireChange);
    public function findOneByMotorcycle($motorcycle);
    public function findOneByPerformedMaintenance(PerformedFrontTireChange $performedMaintenance);
}
