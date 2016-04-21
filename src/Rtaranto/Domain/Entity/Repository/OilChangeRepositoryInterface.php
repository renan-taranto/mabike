<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\OilChange;

interface OilChangeRepositoryInterface
{
    public function update(OilChange $oilChange);
    public function findOneByMotorcycle($motorcycle);
}
