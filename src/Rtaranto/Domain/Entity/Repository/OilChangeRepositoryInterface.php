<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\OilChange;

interface OilChangeRepositoryInterface
{
    /**
     * @param OilChange $oilChange
     * @return OilChange
     */
    public function update(OilChange $oilChange);
    /**
     * @param type $motorcycle
     * @return OilChange
     */
    public function findOneByMotorcycle($motorcycle);
}
