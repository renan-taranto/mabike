<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\PerformedOilChange;

interface PerformedOilChangeRepositoryInterface
{
    public function update(PerformedOilChange $performedOilChange);
    public function delete(PerformedOilChange $performedOilChange);
    public function findOneByMotorcycleAndId($motorcycle, $performedOilChange);
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
