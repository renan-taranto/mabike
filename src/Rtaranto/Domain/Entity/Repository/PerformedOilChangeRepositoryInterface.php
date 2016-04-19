<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\PerformedOilChange;

interface PerformedOilChangeRepositoryInterface
{
    public function update(PerformedOilChange $performedOilChange);
    public function findOneByMotorcycleAndId($motorcycle, $performedOilChangeId);
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function findAllByMotorcycle($motorcycle);    
}
